# Changelog

Todas as alterações relevantes do Woodmart Child da Zincomed serão registradas
neste arquivo.

O formato segue o [Keep a Changelog](https://keepachangelog.com/pt-BR/1.1.0/)
e o projeto usa [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [2.1.0] - 2026-11-16

Alinha a linha 2.x (Woodmart 8.x) com as versões 1.0.2 e 1.0.3 publicadas na
produção e com a correção de 21/10.

### Changed

- Texto de entrega da página de produto: "Entrega em 1 a 5 dias úteis para
  Portugal Continental." (era a alteração 1.0.3 da produção).

### Changed

- CSS e JavaScript do tema passam a ser versionados pela data do ficheiro
  (`2.1.0.<mtime>`): cada deploy muda o endereço e a Cloudflare deixa de
  servir a cópia anterior.

### Fixed

- Cards da loja e das categorias com as medidas da produção (imagem,
  quantidade, preço e botão), no desktop e no mobile; o Woodmart 8.6 passou
  a base de letra da página para 12px, por isso os tamanhos do tema estão
  agora em px em vez de rem.
- Mobile: botão "Entrar / Registar" com fundo, carrosséis de produtos com
  setas em triângulo e bolinhas em pílula, e etiquetas da tabela do
  carrinho em maiúsculas.
- Página de produto: nome do produto no fim do caminho de navegação, títulos
  das abas em maiúsculas e "−/+" da quantidade a branco.
- Carrinho: opções de envio alinhadas à direita.
- Checkout: "(opcional)" do tamanho e cor do rótulo, "Enviar para uma
  morada diferente?" a preto e aviso de cupão numa linha.
- Grelha da loja e das categorias com 3 colunas no desktop e 2 no mobile,
  como definido no tema: regras antigas em flex encolhiam os cards para 5
  por linha no Woodmart 8.
- Cards de produto da home, das categorias e da busca voltaram a usar o card
  da Zincomed (selos de desconto, fundo branco, borda e botão da produção):
  o `woocommerce/content-product.php` antigo, que o 2.0.0 queria remover para
  deixar o Woodmart montar o loop, tinha ficado no repositório e passava à
  frente do card novo.
- Texto do botão do card como na produção: "Comprar" na página de produto
  (relacionados) e "Adicionar" nas outras listas.
- Carrossel de imagens do WPBakery (banner principal da home, desktop e
  mobile) desenhado com o carrossel do próprio Woodmart. No Woodmart 8.6 o
  modelo padrão do WPBakery ficava vazio, porque depende de estilos que o
  tema não carrega; os links, a troca automática e a velocidade continuam a
  vir das opções do elemento.
- Links "Comprar" dos produtos relacionados montados a partir do link do
  próprio produto, sem herdar os parâmetros do endereço da página atual
  (alteração 1.0.2 da produção).
- Logótipo da marca na página de produto: a meta `image` da marca como array
  sem `id`, ou com outro tipo, já não chega ao `esc_url_raw()` e não causa
  erro 500 (mesma correção aplicada na produção a 21/10).

## [2.0.13] - 2026-09-05

### Fixed

- Seletores do carrinho migrados para a estrutura do Woodmart 8.5.x, que
  removeu os wrappers `.cart-table-section`, `.cart-totals-section` e a classe
  `cart-data-form`.
- Proporção de duas colunas, cabeçalho da tabela e espaçamentos restaurados no
  desktop.
- Fundo cinza, cores, bordas e botão preto do resumo do carrinho igualados à
  produção.
- Campo e botão de cupão, aviso do carrinho e botão “Continuar a Comprar”
  restaurados.
- Controles de quantidade compatibilizados com os novos elementos `button` do
  WooCommerce, preservando o layout responsivo.
- Raio adicional aplicado pelo tema novo à imagem do produto removido.

## [2.0.12] - 2026-09-05

### Fixed

- Tabela de informação adicional novamente centralizada e limitada a 650 px,
  como no site de produção.
- Valores de atributos de taxonomia restaurados para 16 px e 22,4 px de altura
  de linha após a troca do elemento `p` por `.wd-term` no Woodmart 8.5.x.
- Cor dos nomes dos atributos corrigida no acordeão mobile para a nova estrutura
  `.wd-attr-label`.
- Tipografia do conteúdo simples da aba de informações de envio protegida das
  regras de conteúdo introduzidas pelo novo tema pai.

## [2.0.11] - 2026-09-05

### Fixed

- Bordas superior e inferior e fundo branco restaurados no bloco de abas da
  página de produto.
- Parágrafos da descrição restaurados para 16 px e 25,6 px de altura de linha.
- Espaço de 30 px entre a navegação das abas e o conteúdo restaurado.
- Distância horizontal entre os títulos das abas igualada à produção.
- Margem inferior das listas restaurada sem alterar a visualização em acordeão
  no mobile.

## [2.0.10] - 2026-09-05

### Changed

- Texto do botão dos cards personalizados restaurado de “Adicionar” para
  “Comprar”, como na produção.

### Fixed

- Descrição curta da página de produto restaurada para 16 px, incluindo o
  parágrafo interno redefinido pelo Woodmart 8.5.7.
- Peso do título principal e do preço ajustados à versão de produção.
- Área de marca e título dos produtos relacionados fixada em 70 px para manter
  o mesmo alinhamento em produtos com e sem marca.
- Espaçamentos duplicados entre imagem e conteúdo removidos nos relacionados.
- Espaçamento entre letras da marca e altura de linha do preço restaurados.

## [2.0.9] - 2026-09-05

### Changed

- Template personalizado da página de produto migrado para a grade nativa do
  Woodmart 8.5.x (`wd-grid-g`, `wd-grid-col` e variáveis responsivas).
- Personalizações de marca, preço, entrega, descontos, metadados, abas e
  produtos relacionados preservadas no template atualizado.

### Fixed

- Galeria e resumo do produto restaurados em duas colunas no desktop.
- Galeria impedida de ocupar toda a largura e empurrar as informações para
  baixo.
- Título, descrição curta e preço restaurados aos tamanhos da produção apesar
  da nova escala de `rem` do tema pai.
- Empilhamento da galeria e do resumo preservado em telas móveis.

## [2.0.8] - 2026-09-04

### Fixed

- Corrigida a ordem visual dos indicadores do banner: os controles agora ficam
  abaixo da imagem sem alterar a altura ou deslocar o banner.
- Reservado o espaco vertical dos indicadores antes da secao seguinte.

## [2.0.7] - 2026-09-04

### Fixed

- Indicadores do carrossel de banners do WPBakery retirados da sobreposicao da
  imagem e posicionados em uma linha propria abaixo do banner.
- Espacamento vertical do banner restaurado para empurrar corretamente o titulo
  da secao seguinte, como no site atual.

## [2.0.6] - 2026-09-04

### Fixed

- Controles dos carrosseis Owl, Woodmart e WPBakery unificados no formato de
  35 por 6 px usado no site atual.
- Indicadores inativos restaurados em cinza e indicador ativo na cor
  secundaria da identidade visual.
- Paginacao bloqueada com um unico indicador mantida visivel nos carrosseis de
  produtos.
- Borda verde removida tambem da imagem alternativa exibida no hover dos cards
  de produtos.

## [2.0.5] - 2026-09-04

### Fixed

- Icone personalizado de favoritos migrado para o elemento `.wd-action-icon`
  da estrutura atual do Woodmart 8.5.3.
- Removida a duplicacao com o coracao nativo durante o hover, preservando o
  mesmo desenho, tamanho, fundo, sombra e estado ativo do site anterior.

## [2.0.4] - 2026-09-04

### Fixed

- Borda verde removida das imagens nos cards da nova estrutura.
- Estrelas restauradas para 18 px, como na versao de producao.
- Preco original riscado voltou a ser exibido sem fundo amarelo.
- Conteudo interno reposicionado 8 px para cima, restaurando a altura dos cards.
- Indicador unico do carrossel voltou a aparecer mesmo quando o Woodmart marca
  a paginacao como bloqueada.
- Indicadores do novo carrossel receberam o formato de 35 por 6 px e a cor
  secundaria usada na versao anterior.

## [2.0.3] - 2026-09-04

### Fixed

- Altura dos cards da Home aproximada novamente dos 435 px da versao anterior.
- Margens adicionais da imagem removidas na estrutura do Woodmart 8.5.3.
- Espacamento interno do card restaurado para 8 px.
- Preco restaurado para 30 px de altura e padding horizontal de 30 px no desktop.
- Botao de compra limitado a 30 px de altura.
- Espaco entre quantidade e botao restaurado no desktop, com ajuste proprio no mobile.

## [2.0.2] - 2026-09-04

### Changed

- Cards e carrosseis de produtos passaram a reconhecer a estrutura atual
  `.wd-products-element`, `.wd-product` e `.wd-product-wrapper` do Woodmart 8.5.3.
- Estilos de quantidade, preco e botao foram ajustados ao novo markup sem remover
  a compatibilidade com os seletores antigos.

### Fixed

- Aparencia dos cards da Home restaurada: fundo, borda, raio, espacamento interno,
  titulo, avaliacao, preco, quantidade e CTA.
- Altura e distribuicao vertical dos cards normalizadas nos novos carrosseis.
- Layout responsivo dos cards preservado em telas de ate 768 px.

## [2.0.1] - 2026-09-04

### Added

- Domínio de tradução `zincomed-child` e respetivo carregamento pelo WordPress.
- Arquivos JavaScript próprios para deteção da plataforma e caixas de variações.

### Changed

- Scripts embutidos nos templates passaram a ser carregados com `wp_enqueue_script()`.
- Funções globais próprias receberam o prefixo `zincomed_child_`.
- Textos próprios do child passaram a usar funções de internacionalização.

### Fixed

- Saídas dinâmicas receberam escape adequado ao respetivo contexto HTML, URL ou atributo.
- `REQUEST_URI` passou a ser normalizado e sanitizado antes da verificação da wishlist.
- SVGs deixaram de ser lidos e impressos diretamente pelo PHP e passaram a ser imagens externas com URL escapada.
- Template do blog passou a validar acesso direto, paginação e conteúdo dinâmico.
- HTML de quantidades, subtotais e notas de compra passou a ser filtrado com `wp_kses_post()`.

### Removed

- Override vazio `woocommerce/parts/products.php`.

## [2.0.0] - 2026-09-04

### Added

- Estrutura de versionamento semântico.
- Constante `ZINCOMED_CHILD_VERSION` como versão única para CSS e JavaScript.
- Template interno `content-product-zincomed.php` para preservar o card personalizado.
- Compatibilidade dos scripts com `.wd-product` e com os eventos atuais da wishlist.

### Changed

- Compatibilidade alvo atualizada do Woodmart 7.3.0 para o Woodmart 8.5.3.
- Loop de produtos passou a preservar o controlador nativo do Woodmart 8.5.3.
- Produtos relacionados passaram do Owl Carousel manual para o carousel nativo atual.
- Accordion de categorias passou a complementar a implementação nativa, sem sobrescrevê-la.
- Seletores CSS dos produtos foram adaptados de elementos `li` para a estrutura atual.
- CSS do tema filho passou a depender do handle oficial `woodmart-style`.

### Fixed

- Possível erro fatal causado pelo carregamento de um template do WPBakery removido no Woodmart 8.5.3.
- Interferência global nos hooks usados pelo Quick View.
- Carregamento duplicado do CSS do tema pai.
- Compatibilidade do botão de remover produto na wishlist após atualizações AJAX.

### Removed

- Overrides dos templates de hover copiados do Woodmart 7.3.0.
- Uso ativo da função depreciada `woodmart_get_old_classes()`.
- Markup manual de `owl-stage`, `owl-item` e larguras inline nos produtos relacionados.
