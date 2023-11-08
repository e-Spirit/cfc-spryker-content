const reloadContent = (node, fsPageId, locale, sectionId, sibling) => {
  disable(node);

  console.log({ fsPageId, locale, sectionId });
  // If not is the slot itself, it is the first section within the slot
  // Otherwise node will be the section with data-preview-id set
  const isFirstSectionInSlot = node.hasAttribute('data-fcecom-slot-name');
  const params = new URLSearchParams({
    ...(isFirstSectionInSlot ? { wrap: true } : null),
    locale,
    fsPageId,
    sectionId,
  });
  fetch(`/fs-preview/cms-block-render?${params}`)
    .then((response) => response.json())
    .then((data) => {
      console.log(node, data);
      if (data.error) {
        console.error('Failed to load partial content', data.error);
        location.reload();
      } else if (data?.renderResult) {
        if (data.renderResult.includes('<script>')) {
          console.log('Script tag detected, reloading page');
          return location.reload();
        }
        !sibling
          ? (node.innerHTML = data.renderResult)
          : node
              .querySelector(`[data-preview-id="${sibling}"]`)
              .insertAdjacentHTML('afterend', data.renderResult);
        enable(node);
      }
    })
    .catch((err) => {
      console.error('Failed to load partial content', err);
      location.reload();
    });
};
FCECOM.addHook('contentChanged', (args) => {
  if (!args.node) {
    // This is not the invocation for the section which can be ignored
    return;
  }
  if (!args.content) {
    // Something has been deleted
    return args.node.remove();
  }
  const fsPageId = document.body.dataset.fsPreviewId.split('.')[0];
  const locale = args.previewId.split('.')[1];
  const sectionId = args.content.identifier;
  return reloadContent(args.node, fsPageId, locale, sectionId);
});
FCECOM.addHook('sectionCreated', (args) => {
  const fsPageId = document.body.dataset.fsPreviewId.split('.')[0];
  const locale = args.pageId.split('.')[1];
  const sectionId = args.identifier;
  const node = document.querySelector(
    `[data-fcecom-slot-name="${args.slotName}"]`
  );
  const sibling = args.siblingPreviewId;
  return reloadContent(node, fsPageId, locale, sectionId, sibling);
});
FCECOM.addHook('requestPreviewElement', (args) => {
  const locale = args.previewId.split('.')[1];
  const pageId = args.previewId.split('.')[0];
  const params = new URLSearchParams({
    locale,
    pageId,
  });
  disable();
  fetch(`/getContentPageUrl?${params}`)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.url) {
        window.location.href = data.url;
      }
    })
    .catch((err) => {
      console.log('Failed to get URL', err);
    });
});

// Overwrite default hook implementation to include disable()
FCECOM.addHook('openStoreFrontUrl', (payload) => {
  if (payload.id === 'homepage') {
    disable();
    window.location.href = '/';
    return;
  }
  const targetUrl = window.location.origin + payload.url;
  if (payload.url && !window.location.href.includes(targetUrl)) {
    disable();
    window.location.href = targetUrl;
  }
});