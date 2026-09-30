(function() {
  const SELECT_SELECTOR = 'select#pa_tamanho, select[name="attribute_pa_tamanho"]';
  const FORM_SELECTOR = 'form.variations_form';

  // util: remove tags HTML
  function stripHtml(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return tmp.textContent || tmp.innerText || '';
  }

  function getVariationsData(form) {
    if (!form) return null;
    const raw = form.getAttribute('data-product_variations') || form.dataset.productVariations || form.dataset
      .product_variations;
    if (!raw) return null;
    try {
      // raw pode estar JSON-escaped (com &quot;), tentar unescape
      const jsonText = raw.trim();
      // se começa com '[' então já é JSON
      if (/^\s*\[/.test(jsonText)) {
        return JSON.parse(jsonText);
      } else {
        // tentar substituir &quot; e depois parse
        const unescaped = jsonText.replace(/&quot;/g, '"').replace(/&amp;/g, '&');
        return JSON.parse(unescaped);
      }
    } catch (e) {
      console.warn('Could not parse product_variations JSON', e);
      return null;
    }
  }

  function buildOptions(select, variations) {
    if (!select) return null;
    if (select.dataset.converted === '1') return null;
    select.dataset.converted = '1';
    select.classList.add('size-converter-select');

    const container = document.createElement('div');
    container.className = 'size-options';

    Array.from(select.options).forEach((opt) => {
      const val = opt.value;
      let text = opt.textContent.trim();
      if (!val) return; // placeholder

      // achar variação correspondente nas variations (matching attribute_pa_tamanho)
      let priceText = '';
      if (variations && Array.isArray(variations)) {
        const found = variations.find(v => {
          if (!v.attributes) return false;
          // várias formas de key; preferimos attribute_pa_tamanho
          return v.attributes['attribute_pa_tamanho'] === val || v.attributes['attribute_tamanho'] === val ||
            v.attributes['attribute_pa_tamanho'] === val.replace(/\s+/g, '-');
        });
        if (found) {
          // price_html sometimes like '<span class="price">3.57€</span>'
          if (found.price_html) priceText = stripHtml(found.price_html).trim();
          else if (typeof found.display_price !== 'undefined') {
            // número: formatar com 2 decimais + símbolo euro
            priceText = Number(found.display_price).toFixed(2) + '€';
          }
        }
      }

      // Atualiza texto do option para incluir preço (texto simples)
      if (priceText) {
        // evita duplicar se já tiver o preço
        const baseText = opt.getAttribute('data-base-text') || text;
        opt.setAttribute('data-base-text', baseText);
        opt.text = baseText + ' — ' + priceText;
      }

      // label visual (contém input invisível + span com size + price)
      const label = document.createElement('label');
      label.className = 'size-label';
      label.setAttribute('data-val', val);

      const input = document.createElement('input');
      input.type = 'radio';
      input.name = 'product_size_converted';
      input.value = val;

      if (select.value === val) {
        label.classList.add('is-checked');
        input.checked = true;
      }

      const span = document.createElement('span');
      span.className = 'size-text';
      span.textContent = text;

      // price element dentro do quadrado
      const priceEl = document.createElement('small');
      priceEl.className = 'size-price';
      priceEl.textContent = priceText || '';

      label.appendChild(input);
      label.appendChild(span);
      label.appendChild(priceEl);
      container.appendChild(label);

      label.addEventListener('click', function() {
        if (!input.checked) input.checked = true;
        select.value = val;
        const evt = new Event('change', {
          bubbles: true
        });
        select.dispatchEvent(evt);

        container.querySelectorAll('.size-label').forEach(l => l.classList.remove('is-checked'));
        label.classList.add('is-checked');
      });
    });

    return container;
  }

  function placeBelowAddToCart(select) {
    const form = document.querySelector(FORM_SELECTOR);
    const variationsData = getVariationsData(form);
    const targetBox = document.querySelector('.wd-variations-boxes') || (function() {
      const addWrap = document.querySelector('.wd-add-to-cart-wrap');
      if (addWrap && addWrap.parentNode) {
        const fallback = document.createElement('div');
        fallback.className = 'wd-variations-boxes';
        addWrap.parentNode.insertBefore(fallback, addWrap.nextSibling);
        return fallback;
      }
      return document.body;
    })();

    // limpa se já houver
    targetBox.querySelectorAll('.size-label-row, .size-options, .reset-wrapper').forEach(n => n.remove());

    const labelText = (() => {
      const origTh = select.closest('tr') ? select.closest('tr').querySelector('th.label.cell') : null;
      return origTh ? origTh.textContent.trim() : 'Opção';
    })();

    const labelRow = document.createElement('div');
    labelRow.className = 'size-label-row';
    labelRow.textContent = labelText;

    const container = buildOptions(select, variationsData);
    if (!container) return;

    // reset link handling (clona)
    const origTr = select.closest('tr');
    const resetOriginal = origTr ? origTr.querySelector('.wd-reset-var .reset_variations') : null;
    let resetWrapper = null;
    if (resetOriginal) {
      const clone = resetOriginal.cloneNode(true);
      clone.style.visibility = resetOriginal.style.visibility || 'hidden';
      clone.addEventListener('click', function() {
        setTimeout(() => {
          container.querySelectorAll('.size-label').forEach(l => l.classList.remove('is-checked'));
        }, 50);
      });
      resetWrapper = document.createElement('div');
      resetWrapper.className = 'reset-wrapper';
      resetWrapper.appendChild(clone);
    }

    targetBox.appendChild(labelRow);
    targetBox.appendChild(container);
    if (resetWrapper) targetBox.appendChild(resetWrapper);

    // esconder origem
    if (origTr) origTr.style.display = 'none';

    // sincronia quando select muda (por WooCommerce)
    select.addEventListener('change', function() {
      const v = select.value;
      container.querySelectorAll('.size-label').forEach(l => {
        const inp = l.querySelector('input');
        if (l.getAttribute('data-val') === v) {
          l.classList.add('is-checked');
          if (inp) inp.checked = true;
        } else {
          l.classList.remove('is-checked');
          if (inp) inp.checked = false;
        }
      });

      if (resetOriginal) resetOriginal.style.visibility = select.value ? 'visible' : 'hidden';
    });

    // inicial: atualiza reset visibility
    if (resetOriginal) resetOriginal.style.visibility = select.value ? 'visible' : 'hidden';
  }

  function init() {
    const select = document.querySelector(SELECT_SELECTOR);
    if (!select) return;
    placeBelowAddToCart(select);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else init();

  // observer caso WooCommerce re-renderize o select/form
  const root = document.querySelector('form.variations_form') || document.body;
  const mo = new MutationObserver(() => {
    const sel = document.querySelector(SELECT_SELECTOR);
    if (sel && sel.dataset.converted !== '1') {
      placeBelowAddToCart(sel);
    }
  });
  mo.observe(root, {
    childList: true,
    subtree: true
  });
})();
