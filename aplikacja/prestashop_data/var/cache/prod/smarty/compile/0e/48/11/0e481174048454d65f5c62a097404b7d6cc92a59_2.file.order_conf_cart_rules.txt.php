<?php
/* Smarty version 3.1.48, created on 2023-12-14 01:30:59
  from '/var/www/html/mails/_partials/order_conf_cart_rules.txt' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_657a4cc301d1d5_90644501',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0e481174048454d65f5c62a097404b7d6cc92a59' => 
    array (
      0 => '/var/www/html/mails/_partials/order_conf_cart_rules.txt',
      1 => 1702419344,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_657a4cc301d1d5_90644501 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'cart_rule');
$_smarty_tpl->tpl_vars['cart_rule']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cart_rule']->value) {
$_smarty_tpl->tpl_vars['cart_rule']->do_else = false;
?>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart_rule']->value['voucher_name'], ENT_QUOTES, 'UTF-8');?>
  <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart_rule']->value['voucher_reduction'], ENT_QUOTES, 'UTF-8');?>

<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
