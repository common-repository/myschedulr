<div class="ms4wp-kvp">
	<p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color">
        <?php echo __( 'Your WordPress instance is connected to your MySchedulr account. If you would like to
		unlink your WordPress instance from your account, please click the "Unlink" button below.', 'myschedulr' ); ?>
		<strong><?php echo __('Unlinking your account is permanent and cannot be undone.', 'myschedulr' ); ?></strong>
	</p>
</div>

<div class="ms4wp-kvp">
	<form name="disconnect" action="" method="post">
		<input type="hidden" name="hidden_action" value="disconnect" />
		<input name="disconnect_button"
               type="submit"
               class="ms4wp-button-text-primary ms4wp-white-text ms4wp-right"
               id="disconnect-instance"
               value="Unlink"
               onclick="return confirm(
                   'Are you sure you want to unlink your MySchedulr account from your WordPress site? This action is permanent and cannot be undone.'
                   )" />
	</form>
</div>
