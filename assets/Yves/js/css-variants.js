FCECOM.addHook('previewInitialized', async ({ TPP_BROKER }) => {
  addCssVariantsButton(TPP_BROKER);
});
const addCssVariantsButton = (TPP_BROKER) => {
  const _variant = Symbol();

  document.head.appendChild(
    document.createElement('style')
  ).innerHTML = `.tpp-icon-variant::after { -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M6.996 12.713V5.584a.87.87 0 01.87-.871h9.13v6M5.996 12.737v3h4.372v3.018a1 1 0 001 1h1a1 1 0 001-1v-3.018h4.628v-3zM9.514 8.923v-4M12.003 7.923v-3M14.491 6.923v-2"/></svg>') center center / cover no-repeat; }`;

  const wait = (millis = 0) =>
    new Promise((resolve) => setTimeout(() => resolve(), millis));

  const callCssVariantsScript = async (action, $node, status) => {
    const { id: dataProviderId, language, componentPath = [] } = status;
    const [listEditorName, listEditorIndex] = componentPath;
    const node = $node.matches('[data-variant-editor-name]')
      ? $node
      : $node.querySelector('[data-variant-editor-name]');
    const { variant: variantEditorValue, variantEditorName } = node.dataset;
    const payload = {
      action,
      dataProviderId: `${dataProviderId}`,
      language,
      ...(listEditorName && listEditorIndex
        ? { listEditorName, listEditorIndex }
        : {}),
      variantEditorName,
      variantEditorValue,
    };
    return await TPP_BROKER.execute('script:css_variants', payload);
  };

  const saveChanges = async ({ $node, status }) => {
    disable($node);
    callCssVariantsScript('save', $node, status);
    const previewId = $node.closest(
      '[data-preview-id]:not([data-preview-id^="#"])'
    ).dataset.previewId;
    TPP_BROKER.triggerChange(previewId);
    enable($node);
  };

  TPP_BROKER.registerButton({
    css: 'tpp-icon-variant',
    supportsComponentPath: true,
    isVisible: async (scope) => {
      const isVisible =
        (scope.$node.matches('[data-variant-editor-name]') ||
          scope.$node.querySelector('[data-variant-editor-name]')) &&
        scope.status?.permissions?.change;

      return isVisible;
    },
    isEnabled: () => true,
    getItems: async ({ $node, status, $button, language }) => {
      const node = $node.matches('[data-variant-editor-name]')
        ? $node
        : $node.querySelector('[data-variant-editor-name]');

      node[_variant] = node.dataset.variant || '';
      const variantValues = await callCssVariantsScript('labels', node, status);
      if (variantValues.success === false) {
        console.warn('Unable to retrieve variant names', {
          variantValues,
          node,
          status,
        });
        return [];
      }
      const values = variantValues.map(({ value }) => value);
      wait(1).then(() => {
        Array.from($button.querySelectorAll('li')).forEach((el, i) => {
          el.addEventListener(
            'mouseenter',
            () => ($node.dataset.variant = values[i])
          );
          el.addEventListener(
            'mouseleave',
            () => ($node.dataset.variant = $node[_variant])
          );
        });
      });
      return variantValues.map(({ value, label }) => {
        return { value, label: label + (value == $node[_variant] ? ' ✓' : '') };
      });
    },
    execute: async (scope, item) => {
      const node = scope.$node.matches('[data-variant-editor-name]')
        ? scope.$node
        : scope.$node.querySelector('[data-variant-editor-name]');
      if (item && node[_variant] != item.value) {
        node.dataset.variant = node[_variant] = item.value;
        await saveChanges(scope);
      }
    },
  });
};