// =============================================
// AcuSport App - JavaScript Principal
// =============================================

document.addEventListener('DOMContentLoaded', () => {
    initAccordions();
    initAnimations();
});

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'default', duration = 2500) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', gold: 'fa-shopping-bag', default: 'fa-info-circle' };
    toast.innerHTML = `<i class="fas ${icons[type] || icons.default}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ===== CART OPERATIONS =====
function addToCart(productId, qty = 1) {
    const btn = event ? event.currentTarget : null;
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }
    
    fetch(`${API_URL}/cart.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'add', product_id: productId, quantidade: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cart_count);
            showToast('Produto adicionado ao carrinho!', 'gold');
        } else {
            showToast(data.message || 'Erro ao adicionar', 'error');
        }
    })
    .catch(() => showToast('Erro de conexão', 'error'))
    .finally(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Adicionar'; }
    });
}

function updateCartQty(itemId, qty) {
    fetch(`${API_URL}/cart.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'update', item_id: itemId, quantidade: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cart_count);
            if (qty <= 0) {
                const item = document.querySelector(`[data-item-id="${itemId}"]`);
                if (item) { item.style.opacity = '0'; item.style.transform = 'translateX(-100%)'; setTimeout(() => { item.remove(); updateCartSummary(); checkEmptyCart(); }, 300); }
            } else {
                updateCartSummary();
            }
        }
    });
}

function removeCartItem(itemId) {
    fetch(`${API_URL}/cart.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'remove', item_id: itemId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cart_count);
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            if (item) {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(-100%)';
                setTimeout(() => { item.remove(); updateCartSummary(); checkEmptyCart(); }, 300);
            }
            showToast('Produto removido', 'default');
        }
    });
}

function updateCartBadge(count) {
    let badge = document.getElementById('cart-badge');
    const navCart = document.querySelector('#nav-cart .nav-icon');
    if (count > 0) {
        if (!badge && navCart) {
            badge = document.createElement('span');
            badge.id = 'cart-badge';
            badge.className = 'cart-badge';
            navCart.appendChild(badge);
        }
        if (badge) { badge.textContent = count; badge.style.animation = 'none'; badge.offsetHeight; badge.style.animation = 'pulse 2s infinite'; }
    } else {
        if (badge) badge.remove();
    }
}

function updateCartSummary() {
    const items = document.querySelectorAll('.cart-item');
    let subtotal = 0;
    items.forEach(item => {
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.querySelector('.qty-value')?.value || 1);
        subtotal += price * qty;
    });

    // Plan Discounts
    let plan_discount_pct = 0;
    if (typeof USER_PLAN !== 'undefined') {
        if (USER_PLAN === 'vitalidade') plan_discount_pct = 0.15;
        if (USER_PLAN === 'mestre') plan_discount_pct = 0.25;
    }
    
    const discount_amount = subtotal * plan_discount_pct;
    const subtotal_after_discount = subtotal - discount_amount;

    const shipping_threshold = 60;
    const shipping_cost = 4.90;
    const free_shipping = (subtotal_after_discount >= shipping_threshold) || (typeof USER_PLAN !== 'undefined' && USER_PLAN !== null);
    const total = free_shipping ? subtotal_after_discount : subtotal_after_discount + shipping_cost;

    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');
    if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
    if (totalEl) totalEl.textContent = total.toFixed(2).replace('.', ',') + ' €';
    
    // Update discount row if it exists or create it
    let discountRow = document.getElementById('cart-discount-row');
    if (discount_amount > 0) {
        if (!discountRow) {
            const summaryRow = document.createElement('div');
            summaryRow.className = 'summary-row';
            summaryRow.id = 'cart-discount-row';
            summaryRow.style.color = 'var(--success)';
            
            const labelSpan = document.createElement('span');
            labelSpan.textContent = 'Desconto Clube (' + (plan_discount_pct * 100) + '%)';
            
            const valueSpan = document.createElement('span');
            valueSpan.id = 'cart-discount-amount';
            valueSpan.style.whiteSpace = 'nowrap';
            valueSpan.textContent = '-' + discount_amount.toFixed(2).replace('.', ',') + ' €';
            
            summaryRow.appendChild(labelSpan);
            summaryRow.appendChild(valueSpan);
            
            const shippingRow = document.querySelectorAll('.summary-row')[1];
            if (shippingRow) shippingRow.parentNode.insertBefore(summaryRow, shippingRow.nextSibling);
        } else {
            const valSpan = document.getElementById('cart-discount-amount');
            if (valSpan) valSpan.textContent = '-' + discount_amount.toFixed(2).replace('.', ',') + ' €';
        }
    } else {
        if (discountRow) discountRow.remove();
    }

    // Atualizar custo de envio na secção de Resumo
    const summaryRows = document.querySelectorAll('.summary-row');
    if (summaryRows.length >= 2) {
        const shippingValueEl = summaryRows[1].querySelector('span:last-child');
        if (shippingValueEl) {
            if (free_shipping) {
                shippingValueEl.className = 'shipping-free';
                shippingValueEl.innerHTML = '<i class="fas fa-check-circle"></i> Grátis';
            } else {
                shippingValueEl.className = 'shipping-paid';
                shippingValueEl.textContent = shipping_cost.toFixed(2).replace('.', ',') + ' €';
            }
        }
    }

    // Atualizar a barra de portes grátis
    const progressSection = document.querySelector('.shipping-progress-section');
    if (progressSection) {
        const msgEl = progressSection.querySelector('.shipping-msg');
        const fillEl = progressSection.querySelector('.shipping-bar-fill');
        
        let progress_pct = (subtotal_after_discount / shipping_threshold) * 100;
        if (progress_pct > 100) progress_pct = 100;
        
        if (fillEl) fillEl.style.width = progress_pct + '%';
        
        if (msgEl) {
            const is_member = (typeof USER_PLAN !== 'undefined' && USER_PLAN !== null);
            
            if (is_member) {
                msgEl.className = 'shipping-msg free';
                msgEl.style.color = 'var(--sage-dark)';
                msgEl.innerHTML = '<i class="fas fa-check-circle"></i> <span>Benefício Clube AcuSport: <strong>Portes Grátis</strong> garantidos.</span>';
            } else if (free_shipping) {
                msgEl.className = 'shipping-msg free';
                msgEl.innerHTML = '<i class="fas fa-check-circle"></i> <span>Parabéns! Tem <strong>portes grátis</strong> nesta encomenda.</span>';
            } else {
                const remaining = shipping_threshold - subtotal_after_discount;
                msgEl.className = 'shipping-msg';
                msgEl.innerHTML = '<i class="fas fa-truck"></i> <span>Faltam <strong>' + remaining.toFixed(2).replace('.', ',') + ' €</strong> para portes grátis!</span>';
            }
        }
    }
}

function checkEmptyCart() {
    const items = document.querySelectorAll('.cart-item');
    if (items.length === 0) {
        invalidateMultibancoRef();
        location.reload();
    }
}

// ===== QUANTITY SELECTOR =====
function changeQty(input, delta) {
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
}

function changeCartQty(itemId, input, delta) {
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    updateCartQty(itemId, val);
}

// ===== SEARCH =====
let searchTimeout;
function searchProducts(query) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        if (query) { url.searchParams.set('search', query); } else { url.searchParams.delete('search'); }
        url.searchParams.set('page', 'shop');
        window.location.href = url.toString();
    }, 500);
}

// ===== CATEGORY FILTER =====
function filterCategory(slug) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', 'shop');
    if (slug) { url.searchParams.set('category', slug); } else { url.searchParams.delete('category'); }
    url.searchParams.delete('search');
    window.location.href = url.toString();
}

// ===== ACCORDIONS =====
function initAccordions() {
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const accordion = header.parentElement;
            accordion.classList.toggle('open');
        });
    });
}

// ===== ANIMATIONS (Intersection Observer) =====
function initAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
}

// ===== AUTH =====
function handleLogin(e) {
    e.preventDefault();
    const form = e.target;
    const data = { action: 'login', email: form.email.value, password: form.password.value };
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A entrar...';

    fetch(`${API_URL}/auth.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast('Bem-vindo!', 'success'); setTimeout(() => window.location.href = BASE_URL + '/?page=home', 800); }
        else { showToast(res.message, 'error'); btn.disabled = false; btn.innerHTML = 'Entrar'; }
    })
    .catch(() => { showToast('Erro de conexão', 'error'); btn.disabled = false; btn.innerHTML = 'Entrar'; });
}

function handleRecover(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A enviar...';

    fetch(`${API_URL}/auth.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'recover', email: form.email.value })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast(res.message, 'success', 4000); form.reset(); }
        else { showToast(res.message, 'error'); }
        btn.disabled = false; btn.innerHTML = originalText;
    })
    .catch(() => { showToast('Erro de conexão', 'error'); btn.disabled = false; btn.innerHTML = originalText; });
}

function handleResetPassword(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A atualizar...';

    fetch(`${API_URL}/auth.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'reset_password', token: form.token.value, password: form.password.value })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { 
            showToast('Password atualizada com sucesso!', 'success'); 
            setTimeout(() => window.location.href = BASE_URL + '/?page=login', 1500); 
        } else { 
            showToast(res.message, 'error'); 
            btn.disabled = false; btn.innerHTML = originalText; 
        }
    })
    .catch(() => { showToast('Erro de conexão', 'error'); btn.disabled = false; btn.innerHTML = originalText; });
}

function handleRegister(e) {
    e.preventDefault();
    const form = e.target;
    if (form.password.value !== form.password_confirm.value) { showToast('As passwords não coincidem', 'error'); return; }
    const data = { action: 'register', nome: form.nome.value, email: form.email.value, password: form.password.value, telefone: form.telefone.value };
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A registar...';

    fetch(`${API_URL}/auth.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast('Conta criada com sucesso!', 'success'); setTimeout(() => window.location.href = BASE_URL + '/?page=home', 800); }
        else { showToast(res.message, 'error'); btn.disabled = false; btn.innerHTML = 'Criar Conta'; }
    })
    .catch(() => { showToast('Erro de conexão', 'error'); btn.disabled = false; btn.innerHTML = 'Criar Conta'; });
}

function handleCheckout(e) {
    e.preventDefault();
    const form = e.target;
    const paymentMethod = form.querySelector('input[name="pagamento"]:checked')?.value || 'cartao';
    
    // Validate payment details
    if (paymentMethod === 'cartao') {
        const cardNum = form.card_number?.value?.replace(/\s/g, '') || '';
        const cardExpiry = form.card_expiry?.value || '';
        const cardCvv = form.card_cvv?.value || '';
        const cardName = form.card_name?.value || '';
        if (cardNum.length < 16 || !cardExpiry || !cardCvv || !cardName) {
            showToast('Preencha todos os dados do cartão', 'error');
            return;
        }
    } else if (paymentMethod === 'mbway') {
        const phone = form.mbway_phone?.value?.replace(/\s/g, '') || '';
        if (phone.length < 9) {
            showToast('Introduza um número de telemóvel válido', 'error');
            return;
        }
    } else if (paymentMethod === 'multibanco') {
        // Store checkout data for later confirmation
        window._mbCheckoutData = {
            nome: form.nome.value, email: form.email.value, telefone: form.telefone?.value || '',
            morada: form.morada.value, codigo_postal: form.codigo_postal.value, cidade: form.cidade.value,
            metodo_pagamento: 'multibanco',
            notas: form.notas?.value || ''
        };
        window._mbCheckoutType = 'order';
        showMultibancoCard();
        return;
    }
    
    const data = {
        nome: form.nome.value, email: form.email.value, telefone: form.telefone?.value || '',
        morada: form.morada.value, codigo_postal: form.codigo_postal.value, cidade: form.cidade.value,
        metodo_pagamento: paymentMethod,
        notas: form.notas?.value || ''
    };
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A processar...';

    fetch(`${API_URL}/orders.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { window.location.href = BASE_URL + '/?page=order-success&order_id=' + res.order_id; }
        else { showToast(res.message, 'error'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-lock"></i> Confirmar Encomenda'; }
    })
    .catch(() => { showToast('Erro de conexão', 'error'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-lock"></i> Confirmar Encomenda'; });
}

// ===== PLAN CHECKOUT (Multibanco intercept) =====
function handlePlanCheckout(e) {
    const form = e.target;
    const paymentMethod = form.querySelector('input[name="pagamento"]:checked')?.value || 'mbway';
    
    if (paymentMethod === 'multibanco') {
        e.preventDefault();
        window._mbCheckoutType = 'plan';
        window._mbPlanForm = form;
        showMultibancoCard();
        return;
    }
    // For other methods, let the form submit normally (POST)
}

function selectPayment(el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    const radio = el.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    // Show/hide payment detail panels
    const method = radio ? radio.value : '';
    document.querySelectorAll('.payment-details').forEach(panel => {
        panel.style.display = 'none';
    });
    const activePanel = document.getElementById('payment-details-' + method);
    if (activePanel) {
        activePanel.style.display = 'block';
    }
}

// ===== CARD NUMBER FORMATTER =====
function formatCardNumber(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 16);
    let formatted = v.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = formatted;
}

// ===== EXPIRY DATE FORMATTER =====
function formatExpiry(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) {
        v = v.substring(0, 2) + '/' + v.substring(2);
    }
    input.value = v;
}

// ===== PHONE FORMATTER =====
function formatPhone(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 9);
    if (v.length > 6) {
        v = v.substring(0, 3) + ' ' + v.substring(3, 6) + ' ' + v.substring(6);
    } else if (v.length > 3) {
        v = v.substring(0, 3) + ' ' + v.substring(3);
    }
    input.value = v;
}

// ===== MULTIBANCO FULLSCREEN OVERLAY =====
// Shows the Multibanco overlay after the user clicks "Confirmar Encomenda" or "Finalizar Subscrição"
function showMultibancoCard() {
    const overlay = document.getElementById('mb-overlay');
    if (!overlay) return;

    // Show overlay with fade-in animation
    overlay.style.display = 'flex';
    overlay.style.opacity = '0';
    overlay.style.transition = 'opacity 0.35s ease';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
    });

    // Generate reference only if one doesn't already exist or has expired
    if (!window._mbCountdown) {
        generateMultibancoRef();
    } else {
        // Update the value in case the cart changed, but keep the same reference
        const valorEl = document.getElementById('mb-valor');
        if (valorEl && typeof checkoutTotal !== 'undefined') {
            valorEl.textContent = checkoutTotal.toFixed(2).replace('.', ',') + ' €';
        }
    }
}

// Close the Multibanco overlay (back button)
function closeMultibancoOverlay() {
    const overlay = document.getElementById('mb-overlay');
    if (!overlay) return;

    overlay.style.opacity = '0';
    setTimeout(() => {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 350);
}

// Confirm the Multibanco payment — submits the order and redirects
function confirmMultibancoPayment() {
    const btn = document.getElementById('btn-mb-confirm');
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A processar...';

    if (window._mbCheckoutType === 'order') {
        // Submit the order via API
        fetch(`${API_URL}/orders.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(window._mbCheckoutData)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Clear MB state
                if (window._mbCountdown) { clearInterval(window._mbCountdown); window._mbCountdown = null; }
                window.location.href = BASE_URL + '/?page=order-success&order_id=' + res.order_id;
            } else {
                showToast(res.message, 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shield-alt" style="font-size: 1.15rem;"></i><span>Confirmar Pagamento</span>';
            }
        })
        .catch(() => {
            showToast('Erro de conexão', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shield-alt" style="font-size: 1.15rem;"></i><span>Confirmar Pagamento</span>';
        });
    } else if (window._mbCheckoutType === 'plan') {
        // Submit the plan form normally (POST)
        if (window._mbPlanForm) {
            // Clear MB state
            if (window._mbCountdown) { clearInterval(window._mbCountdown); window._mbCountdown = null; }
            window._mbPlanForm.submit();
        }
    }
}

// ===== MULTIBANCO REFERENCE GENERATOR =====
function generateMultibancoRef() {
    // Clear any previous countdown
    if (window._mbCountdown) {
        clearInterval(window._mbCountdown);
    }

    // Generate random 9-digit reference (3 groups of 3)
    const r1 = String(Math.floor(Math.random() * 1000)).padStart(3, '0');
    const r2 = String(Math.floor(Math.random() * 1000)).padStart(3, '0');
    const r3 = String(Math.floor(Math.random() * 1000)).padStart(3, '0');
    const reference = r1 + ' ' + r2 + ' ' + r3;

    const refEl = document.getElementById('mb-referencia');
    const valorEl = document.getElementById('mb-valor');
    const countdownEl = document.getElementById('mb-countdown');
    const refBox = document.getElementById('mb-ref-box');
    const confirmBtn = document.getElementById('btn-mb-confirm');

    if (refEl) refEl.textContent = reference;
    if (valorEl && typeof checkoutTotal !== 'undefined') {
        valorEl.textContent = checkoutTotal.toFixed(2).replace('.', ',') + ' €';
    }

    // Reset confirm button
    if (confirmBtn) {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-shield-alt" style="font-size: 1.15rem;"></i><span>Confirmar Pagamento</span>';
    }

    // 10-minute countdown
    let remaining = 600; // 10 minutes in seconds
    window._mbExpiry = Date.now() + (remaining * 1000);

    function updateCountdown() {
        const now = Date.now();
        const diff = Math.max(0, Math.floor((window._mbExpiry - now) / 1000));
        const mins = Math.floor(diff / 60);
        const secs = diff % 60;

        if (countdownEl) {
            countdownEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        if (diff <= 60 && countdownEl) {
            countdownEl.style.color = '#ff5252';
        }

        if (diff <= 0) {
            clearInterval(window._mbCountdown);
            window._mbCountdown = null;
            // Invalidate reference
            if (refEl) refEl.textContent = '--- --- ---';
            if (valorEl) valorEl.textContent = '---';
            if (countdownEl) {
                countdownEl.textContent = 'EXPIRADO';
                countdownEl.style.color = '#ff5252';
            }
            if (refBox) {
                refBox.style.opacity = '0.5';
                refBox.style.pointerEvents = 'none';
            }
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="fas fa-times-circle" style="font-size: 1.15rem;"></i><span>Referência Expirada</span>';
            }
            showToast('Referência Multibanco expirada. Volte atrás e tente novamente.', 'error');
        }
    }

    // Reset box state
    if (refBox) {
        refBox.style.opacity = '1';
        refBox.style.pointerEvents = 'auto';
    }
    if (countdownEl) countdownEl.style.color = '#ffc107';

    updateCountdown();
    window._mbCountdown = setInterval(updateCountdown, 1000);
}

// ===== INVALIDATE MULTIBANCO REFERENCE =====
// Only called when cart is completely emptied
function invalidateMultibancoRef() {
    if (window._mbCountdown) {
        clearInterval(window._mbCountdown);
        window._mbCountdown = null;
    }
    window._mbExpiry = null;
    const refEl = document.getElementById('mb-referencia');
    const valorEl = document.getElementById('mb-valor');
    const countdownEl = document.getElementById('mb-countdown');
    const refBox = document.getElementById('mb-ref-box');

    if (refEl) refEl.textContent = '--- --- ---';
    if (valorEl) valorEl.textContent = '---';
    if (countdownEl) {
        countdownEl.textContent = 'INVÁLIDO';
        countdownEl.style.color = '#ff5252';
    }
    if (refBox) {
        refBox.style.opacity = '0.5';
        refBox.style.pointerEvents = 'none';
    }
    // Hide the overlay
    closeMultibancoOverlay();
}

function logout() {
    fetch(`${API_URL}/auth.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'logout' })
    })
    .then(() => { window.location.href = BASE_URL + '/?page=home'; });
}

// ===== CLIENT AREA TABS =====
function switchClientTab(tabName, btnEl) {
    // Hide all tab contents
    document.querySelectorAll('.client-tab-content').forEach(tab => tab.classList.remove('active'));
    // Deactivate all tab buttons
    document.querySelectorAll('.client-tab').forEach(btn => btn.classList.remove('active'));
    // Show the selected tab
    const targetTab = document.getElementById('tab-' + tabName);
    if (targetTab) {
        targetTab.classList.add('active');
        // Re-initialize accordions if the tab has them
        initAccordions();
    }
    // Activate clicked button
    if (btnEl) btnEl.classList.add('active');
}

// ===== PROFILE UPDATE =====
function handleUpdateProfile(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A guardar...';

    fetch(`${API_URL}/profile.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update_profile',
            nome: form.nome.value,
            telefone: form.telefone.value
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast(res.message, 'success'); }
        else { showToast(res.message, 'error'); }
    })
    .catch(() => showToast('Erro de conexão', 'error'))
    .finally(() => { btn.disabled = false; btn.innerHTML = originalText; });
}

function handleUpdateAddress(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A guardar...';

    fetch(`${API_URL}/profile.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update_address',
            morada: form.morada.value,
            codigo_postal: form.codigo_postal.value,
            cidade: form.cidade.value
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast(res.message, 'success'); }
        else { showToast(res.message, 'error'); }
    })
    .catch(() => showToast('Erro de conexão', 'error'))
    .finally(() => { btn.disabled = false; btn.innerHTML = originalText; });
}

function handleChangePassword(e) {
    e.preventDefault();
    const form = e.target;
    if (form.new_password.value !== form.confirm_password.value) {
        showToast('As passwords não coincidem', 'error');
        return;
    }
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A alterar...';

    fetch(`${API_URL}/profile.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'change_password',
            current_password: form.current_password.value,
            new_password: form.new_password.value,
            confirm_password: form.confirm_password.value
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { showToast(res.message, 'success'); form.reset(); }
        else { showToast(res.message, 'error'); }
    })
    .catch(() => showToast('Erro de conexão', 'error'))
    .finally(() => { btn.disabled = false; btn.innerHTML = originalText; });
}

// ===== CONTACT FORM =====
function handleContactForm(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('contact-submit-btn');
    const originalText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A enviar...';

    const data = {
        nome: form.nome.value,
        email: form.email.value,
        assunto: form.assunto.value,
        mensagem: form.mensagem.value
    };

    fetch(`${API_URL}/contact.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showToast('Mensagem enviada com sucesso! Entraremos em contacto em breve.', 'success', 3500);
            form.reset();
        } else {
            showToast(res.message || 'Erro ao enviar mensagem', 'error');
        }
    })
    .catch(() => showToast('Erro de conexão', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

// ===== SUBSCRIPTION =====
function openCancelModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function handleCancelPlan(e) {
    const btn = document.getElementById('btnConfirmCancel');
    const originalText = btn.innerHTML;
    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A cancelar...';

    fetch(`${API_URL}/profile.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'cancel_plan' })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeCancelModal();
            showToast(res.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(res.message || 'Erro ao cancelar', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(() => {
        showToast('Erro de conexão', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

