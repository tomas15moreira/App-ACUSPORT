<?php
require_once 'config/database.php';
$db = getDB();

try {
    $produtos = [
        [1, "Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para apoiar processos de emagrecimento, ajudando na redução da gordura corporal e localizada, no combate à celulite e no controlo eficaz do apetite. A sua fórmula completa promove uma sensação de saciedade imediata e estimula o metabolismo através de um efeito termogénico que evita a absorção de gorduras pelo organismo. Para além de contribuir para a diminuição da retenção de líquidos e facilitar o trânsito intestinal, atua também no equilíbrio metabólico, ajudando a controlar os níveis de glicémia e de colesterol."],
        [2, "Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para auxiliar na perda de gordura, sendo especialmente indicado para processos de emagrecimento associados a níveis elevados de colesterol ou glicose no sangue. A sua fórmula combina o mineral Crómio com extratos botânicos como Garcinia Cambogia, Gengibre, Aloé Vera e Cascara Sagrada para atuar de forma dupla no metabolismo e no sistema digestivo."],
        [3, "Este suplemento, disponível numa embalagem de 20 ampolas, foi desenvolvido para combater ativamente a fadiga física, mental e emocional, otimizando o rendimento cerebral, a memória e a capacidade de concentração. A sua fórmula revitalizante combina Vitamina C e vitaminas do complexo B com extratos naturais estimulantes como Guaraná, Ginseng Coreano, Rhodiola Rosea e Gengibre."],
        [4, "Este suplemento, disponível numa embalagem de 60 cápsulas, foi desenvolvido para apoiar o normal funcionamento da estrutura óssea, muscular e das cartilagens, destacando-se pela sua forte ação analgésica e anti-inflamatória. A sua fórmula combina vitaminas C e D, Glucosamina, Condroitina e MSM, potenciados por extratos naturais de Curcuma e Boswellia."],
        [5, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Modo de utilização: Em geral 50 gotas diluídas em água 2 vezes ao dia."],
        [6, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Ação: Retira calor no Qi. Purifica calor perverso. Regenera produção de líquidos orgânicos. Modo de utilização: Em geral 50 gotas diluídas em água 2 vezes ao dia."],
        [7, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Modo de utilização: Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta."],
        [8, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para astenia geral, fadiga crónica, impotência, fraqueza lombar e rejuvenescimento. Tonifica o Yin e o Yang, o Sangue e a Energia."],
        [9, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para lombalgia crónica, dores crónicas das costas e fraqueza dos joelhos. Elimina Vento, frio e humidade; Tonifica o Rim."],
        [10, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para hemorroidas, hemorragias e calor nos intestinos. Modo de utilização: Tomar 50 gotas diluídas em água 2 vezes ao dia."],
        [11, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para hemorroidas, dores epigástricas, prisão de ventre e úlceras da boca. Nutre o Yin do Estômago."],
        [12, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para astenia, cansaço, tonturas, distúrbios de memória e concentração. Tonifica o Yin e Yang em geral."],
        [13, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para má circulação, varizes, prevenção de AVC e enfarte do miocárdio. Ativa a circulação do Sangue e Energia."],
        [14, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para garganta inflamada, dores de cabeça, sintomas de constipação e urticária. Dispersa o Vento e o Calor."],
        [15, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para asma, tosse com expectoração viscosa e respiração difícil. Dispersa e diminui a energia patogénica no Pulmão."],
        [16, "Suplemento alimentar fitoterápico à base de plantas de medicina tradicional chinesa, 100% natural. Indicado para cefaleias de tensão, vertigens, convulsões e entorpecimento dos membros. Acalma o Fígado e alimenta o Yin."],
    ];

    $stmt = $db->prepare("UPDATE products SET descricao_mtc = ? WHERE id = ?");
    
    foreach ($produtos as $p) {
        $stmt->execute([$p[1], $p[0]]);
    }

    echo "<h2 style='color:green'>Descrições actualizadas com sucesso!</h2>";
    echo "<p><a href='/?page=shop'>Ver loja</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
