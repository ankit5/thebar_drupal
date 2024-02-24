jQuery.fn.LBPlusSetLayoutBuilderInactive = () => {
  // Set parent layout builders inactive while a child layout builder is active.
  for (const layoutBuilders of document.querySelectorAll('.layout-builder.active')) {
    layoutBuilders.classList.remove('active');
  }
  document.getElementById('lb-plus-nested-layout-breadcrumb').style.display = 'block';
};
