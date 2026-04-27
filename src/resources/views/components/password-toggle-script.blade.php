<script>
  const handlePasswordToggleClick = event => {
    const toggle = event.target.closest('[data-password-toggle]');
    if (!toggle) {
      return;
    }

    const container = toggle.closest('[data-password-container]') ?? toggle.parentElement;
    const targetId = toggle.dataset.passwordTarget;
    const input = targetId
      ? document.getElementById(targetId)
      : container?.querySelector('input[type="password"], input[type="text"][data-password-input="true"]');

    if (!input) {
      return;
    }

    input.setAttribute('data-password-input', 'true');
    input.type = input.type === 'password' ? 'text' : 'password';

    const isVisible = input.type === 'text';
    const showIcon = toggle.dataset.passwordShowIcon;
    const hideIcon = toggle.dataset.passwordHideIcon;
    const icon = toggle.querySelector('[data-password-icon]');

    if (icon && showIcon && hideIcon) {
      icon.src = isVisible ? hideIcon : showIcon;
      icon.alt = isVisible ? 'Skryť heslo' : 'Zobraziť heslo';
    }

    toggle.setAttribute('aria-label', isVisible ? 'Skryť heslo' : 'Zobraziť heslo');
    toggle.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
  };

  // Capture phase is required because modal containers use @click.stop.
  document.addEventListener('click', handlePasswordToggleClick, true);
</script>