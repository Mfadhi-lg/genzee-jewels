<?php
/**
 * Section: Newsletter CTA
 *
 * Static form — no backend email handling yet.
 * Replace action="#" with the email service endpoint when ready.
 *
 * @package genzee-jewels
 */
?>

<section class="section-newsletter">
	<div class="newsletter-wrap">
		<div class="newsletter-card">
			<div class="newsletter-orb newsletter-orb--1" aria-hidden="true"></div>
			<div class="newsletter-orb newsletter-orb--2" aria-hidden="true"></div>

			<div class="newsletter-content">
				<span class="newsletter-eyebrow">✉️ <?php esc_html_e( 'JOIN THE GROUP CHAT', 'genzee-jewels' ); ?></span>

				<h2 class="newsletter-heading">
					<?php esc_html_e( 'Get', 'genzee-jewels' ); ?>
					<span class="newsletter-heading__accent"><?php esc_html_e( ' 15% off', 'genzee-jewels' ); ?></span>
					<?php esc_html_e( ' + early access', 'genzee-jewels' ); ?>
				</h2>

				<p class="newsletter-sub">
					<?php esc_html_e( 'Weekly drops. Exclusive codes. No spam, promise 🤞', 'genzee-jewels' ); ?>
				</p>

				<form
					class="newsletter-form"
					action="#"
					method="post"
					novalidate
					aria-label="<?php esc_attr_e( 'Newsletter sign-up', 'genzee-jewels' ); ?>"
				>
					<label for="gz-newsletter-email" class="screen-reader-text">
						<?php esc_html_e( 'Your email address', 'genzee-jewels' ); ?>
					</label>
					<input
						id="gz-newsletter-email"
						class="newsletter-form__input"
						type="email"
						name="gz_email"
						placeholder="<?php esc_attr_e( 'your@email.com', 'genzee-jewels' ); ?>"
						required
						autocomplete="email"
					>
					<button class="newsletter-form__btn" type="submit">
						<?php esc_html_e( 'Gimme 15%', 'genzee-jewels' ); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true" focusable="false">
							<path d="M5 12L19 12M13 6L19 12L13 18"/>
						</svg>
					</button>
				</form>
			</div>
		</div>
	</div>
</section>
