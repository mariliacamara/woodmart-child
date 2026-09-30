# Zincomed Woodmart Child 2.0.13

Compatibilidade alvo: Woodmart 8.5.x (validado no Woodmart 8.5.7).

## Versionamento

O child segue [Semantic Versioning](https://semver.org/):

- `MAJOR`: mudança incompatível ou migração para uma nova arquitetura do tema pai;
- `MINOR`: nova funcionalidade compatível;
- `PATCH`: correção compatível, sem nova funcionalidade.

A versão oficial fica no cabeçalho de `style.css`. A constante
`ZINCOMED_CHILD_VERSION` lê esse valor e o utiliza no carregamento de todos os
arquivos CSS e JavaScript, evitando versões divergentes e problemas de cache.

Em cada lançamento:

1. atualizar `Version` em `style.css`;
2. registrar as alterações em `CHANGELOG.md`;
3. gerar o ZIP como `woodmart-child-VERSAO-woodmart-VERSAO.zip`.

## Migração realizada

- controlador nativo dos cards do Woodmart 8.5.3 preservado;
- filtro limitado ao template interno redireciona os estilos de card para o layout Zincomed;
- card visual da Zincomed preservado em `woocommerce/content-product-zincomed.php`;
- classes atuais `wd-product`, `wd-col`, `wd-product-wrapper`, `wd-product-thumb`,
  `wd-product-img-link` e `wd-product-content` adicionadas ao HTML;
- overrides antigos de hover removidos para herdar as versões do Woodmart 8.5.3;
- produtos relacionados migrados do Owl Carousel manual para o carousel nativo;
- override removido do template de carousel do WPBakery que não existe mais no tema pai;
- correção de velocidade do WPBakery mantida através de filtro de atributos;
- sobrescrita integral do accordion de categorias removida;
- scripts de quantidade e wishlist atualizados para a classe `.wd-product`;
- carregamento duplicado do CSS do tema pai removido;
- chamadas a `woodmart_get_old_classes()` removidas dos templates personalizados ativos;
- hooks globais que afetavam o Quick View removidos.

## Segurança e padrões

- escape de saída aplicado conforme o contexto (`esc_html`, `esc_attr`, `esc_url` e `wp_kses_post`);
- entrada baseada em `REQUEST_URI` normalizada com `wp_unslash` e sanitizada;
- SVGs carregados como imagens externas, sem leitura e impressão direta pelo PHP;
- scripts próprios carregados pela API de enqueue do WordPress;
- funções globais próprias prefixadas com `zincomed_child_`;
- domínio de tradução próprio carregado como `zincomed-child`;
- proteção contra acesso direto nos templates PHP ativos.

## Validação recomendada em staging

Validar Home, loja, categorias, pesquisa, wishlist, produto simples, produto variável,
produto sem stock, carrosséis, relacionados, Quick View, carrinho, checkout, Minha Conta
e os e-mails personalizados antes de publicar em produção.
