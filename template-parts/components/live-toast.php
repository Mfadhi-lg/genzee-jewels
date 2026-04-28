<?php
/**
 * Component: Live purchase toast
 *
 * Fixed bottom-left notification. Fake/demo data only.
 * JS in main.js drives rotation and dismiss. Easy to disable
 * by removing this get_template_part() call from front-page.php.
 *
 * @package genzee-jewels
 */
?>

<div
	id="live-toast"
	class="live-toast"
	role="status"
	aria-live="polite"
	aria-atomic="true"
	aria-label="<?php esc_attr_e( 'Recent purchase notification', 'genzee-jewels' ); ?>"
>
	<div class="live-toast__icon" aria-hidden="true">
		<svg width="18" height="18" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
			<path d="M12 2L14 8L20 8.5L15 12L17 18L12 15L7 18L9 12L4 8.5L10 8Z"/>
		</svg>
	</div>

	<div class="live-toast__content">
		<span class="live-toast__name"></span>
		<span class="live-toast__msg"></span>
		<span class="live-toast__time"></span>
	</div>

	<button
		class="live-toast__close"
		type="button"
		aria-label="<?php esc_attr_e( 'Dismiss notification', 'genzee-jewels' ); ?>"
	>
		<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" focusable="false">
			<path d="M18 6L6 18M6 6L18 18"/>
		</svg>
	</button>
</div>
