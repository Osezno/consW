<?php
//********************************************
//	Automotive Contact Form Shortcode
//***********************************************************

echo '<fieldset id="contact_form" class="form_contact ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '">
    <div class="contact_result"></div>
    <input type="text" name="name" class="form-control margin-bottom-25" placeholder=" ' . esc_attr( $name ) . '" />
    <input type="email" name="email" class="form-control margin-bottom-25" placeholder="' . esc_attr( $email ) . '" />
    <textarea name="message" class="form-control margin-bottom-25 contact_textarea" placeholder="' . esc_attr( $message ) . '" rows="7"></textarea>
    <input id="submit_btn" class="submit_contact_form" type="submit" value="' . esc_attr( $button ) . '">
</fieldset>';