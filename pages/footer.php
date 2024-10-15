<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
    --color-neutral-0: #0a0505; /* Vinho escuro */
}

* {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    letter-spacing: 1px;
}

main {
    height: 80vh; /* Pode ser relativo à viewport */
}

footer {
    background-color: var(--color-neutral-0);
    padding: 2rem 3.5rem;
    text-align: left;
    padding-top: 40px;
    position: fixed; /* Fixar o footer na parte inferior */
    bottom: 0; /* Alinha o footer no fundo */
    width: 100%; /* Faz o footer ocupar toda a largura da tela */
    z-index: 1000; /* Garante que o footer fique acima de outros elementos */
}


#footer_content {
    display: flex;
    justify-content: space-between; /* Espaça os conteúdos entre os lados */
    align-items: flex-start; /* Alinha itens ao topo */
    height: auto;
    margin-top: -240px; /* Este valor pode ser ajustado */
}

#footer_contacts {
    margin-bottom: 1rem; /* Margem ajustada para espaçamento */
}

#footer_contacts p {
    color: white; /* Cor do texto */
    font-size: 0.9rem; /* Tamanho do texto ajustado */
    line-height: 1.2; /* Melhor espaçamento entre linhas */
    margin-left: 0; /* Remove a margem esquerda */
    max-width: 300px; /* Limita a largura do texto */
    margin-bottom: 3rem; /* Adiciona margem inferior para espaçamento */
    transform: translateY(220px);
}

#footer_social_media {
    display: flex;
    flex-direction: column; /* Alinha os itens em coluna */
    align-items: flex-end; /* Alinha à direita */
    transform: translateY(165px);
}

#footer_social_media ul {
    list-style: none; /* Remove o estilo padrão de lista */
    padding-left: 0; /* Remove o padding padrão */
    margin-left: 0; /* Remove a margem padrão */
    padding-top: 40px;
}

#footer_social_media li {
    margin-bottom: 0.5rem; /* Espaço entre os itens da lista */
    display: flex; /* Usado para alinhar ícones e texto */
    align-items: center; /* Alinha verticalmente */
}

.footer_link i {
    font-size: 1.0rem; /* Tamanho dos ícones das redes sociais */
    color: white;    
    margin-right: 0.5rem; /* Espaçamento entre o ícone e a bolinha */
}

/* Novo estilo para o ponto da lista */
#footer_social_media li::before {
    content: "•"; /* Usar uma bolinha */
    color: white; /* Cor da bolinha */
    margin-right: 0.5rem; /* Espaçamento entre a bolinha e o ícone */
}

#footer_logos {
    display: flex;
    justify-content: flex-end; /* Alinha as logos à direita */
    padding: 0; /* Remove o padding */
    margin: 1rem 0; /* Espaço vertical */
    width: 100%; /* Garante que as logos ocupem toda a largura disponível */
    max-width: 1000px; /* Limita a largura máxima */
    align-items: center; /* Alinha as logos verticalmente ao centro */
    transform: translateY(45px);
}

.footer-logo {
    width: 80px; /* Tamanho padrão para as imagens */
    height: auto; /* Mantém a proporção da imagem */
    margin: 0 10rem; /* Aumenta o espaço entre as logos */
}

.footer-logo.middle {
    width: 120px; /* Tamanho padrão para a logo do governo */
}

.footer-rights {
    text-align: center; /* Centraliza o texto */
    font-size: 0.800rem; /* Tamanho menor para o texto de direitos reservados */
    color: white; /* Cor do texto */
    transform: translateY(32px);
    margin-bottom: 0%;
    padding-top: 1px;
}

/* Responsivo */
@media screen and (max-width: 768px) {
    #footer_content {
        flex-direction: column; /* Muda a direção para coluna */
        align-items: center; /* Centraliza o conteúdo */
        margin-top: 0; /* Remove margem negativa */
        padding: 1rem; /* Menos padding em telas pequenas */
    }

    #footer_social_media {
        padding-left: 100%;
    }

    #footer_logos {
        flex-direction: row; /* Muda para linha */
        align-items: center; /* Centraliza as logos */
        justify-content: flex-end; /* Alinha à direita */
        margin-top: 1rem; /* Espaço entre o texto e as logos */
    }

    .footer-logo {
        margin: 0 4rem; /* Aumenta o espaço entre as logos no celular */
    }
}

p {
    transform: translateY(200px);
}
    </style>
</head>
<body>
    <main></main>
    <footer>
        <div id="footer_content">
            <div id="footer_contacts">
                <p>
                    Faculdade de Tecnologia de São Paulo - Educação profissional pública<br>
                    superior e de excelência!
                </p>
            </div>
            <div id="footer_social_media">
                <ul>
                    <li><a href="https://wa.me/551938635210" class="footer_link" id="whatsapp" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
                    <li><a href="https://www.facebook.com/fatecitapira?locale=pt_BR" class="footer_link" id="facebook" target="_blank"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="https://www.instagram.com/fatecdeitapira/" class="footer_link" id="instagram" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/company/fatec-de-itapira/?originalSubdomain=br" class="footer_link" id="linkedin" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
                </ul>
            </div>
        </div>
        <div id="footer_logos">
            <a href="https://www.cps.sp.gov.br/"> 
                <img src="../img/cps-removebg-preview.png" alt="Logo do Centro Paula Souza" class="footer-logo">
            </a>
            <a href="https://www.saopaulo.sp.gov.br/"> 
                <img src="../img/logo-footer-governo-do-estado-sp.png" alt="Logo do Governo do Estado de São Paulo" class="footer-logo middle">
            </a>
        </div>
        <p class="footer-rights">© Todos os direitos reservados</p>
    </footer>
    
</body>
</html>