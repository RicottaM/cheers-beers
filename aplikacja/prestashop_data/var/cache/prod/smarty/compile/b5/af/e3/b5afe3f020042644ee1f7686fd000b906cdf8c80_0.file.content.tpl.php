<?php
/* Smarty version 3.1.48, created on 2023-12-14 01:29:52
  from '/var/www/html/admin_fashionables/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_657a4c80b692f6_28514296',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b5afe3f020042644ee1f7686fd000b906cdf8c80' => 
    array (
      0 => '/var/www/html/admin_fashionables/themes/default/template/content.tpl',
      1 => 1702419344,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_657a4c80b692f6_28514296 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
