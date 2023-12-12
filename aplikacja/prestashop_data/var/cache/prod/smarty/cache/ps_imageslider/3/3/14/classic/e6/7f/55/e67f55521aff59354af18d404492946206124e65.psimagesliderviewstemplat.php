<?php
/* Smarty version 3.1.48, created on 2023-12-12 15:38:50
  from 'module:psimagesliderviewstemplat' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6578707a9df1b4_43912119',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6c2108a17c7103b6e203f4f0621d4645b56b0114' => 
    array (
      0 => 'module:psimagesliderviewstemplat',
      1 => 1689769962,
      2 => 'module',
    ),
  ),
  'cache_lifetime' => 31536000,
),true)) {
function content_6578707a9df1b4_43912119 (Smarty_Internal_Template $_smarty_tpl) {
?>
  <div id="carousel" data-ride="carousel" class="carousel slide" data-interval="5000" data-wrap="true" data-pause="hover" data-touch="true">
    <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
            <li data-target="#carousel" data-slide-to="2"></li>
          </ol>
    <ul class="carousel-inner" role="listbox" aria-label="Pokaz slajdów">
              <li class="carousel-item active" role="option" aria-hidden="false">
          <a href="">
            <figure>
              <img src="http://localhost:8080/modules/ps_imageslider/images/4282617d1d584edd414ebe5f3f96a3eb0dac7b2a_BA2505C4-275C-424A-AA00-80869FCA36F7.PNG" alt="Pasja" loading="lazy" width="1110" height="340">
                              <figcaption class="caption">
                  <h2 class="display-1 text-uppercase">Pasja</h2>
                  <div class="caption-description"><h3>Jedyni w swoim rodzaju</h3>
<p>Twój styl, nasza moda - razem tworzymy trendy!</p></div>
                </figcaption>
                          </figure>
          </a>
        </li>
              <li class="carousel-item " role="option" aria-hidden="true">
          <a href="">
            <figure>
              <img src="http://localhost:8080/modules/ps_imageslider/images/b97c734c9e8772d23e15641bfb726b41fc7f6521_8D6C6134-C0FD-4DEB-B4AF-AD9BD4AF3E25.PNG" alt="Moda" loading="lazy" width="1110" height="340">
                              <figcaption class="caption">
                  <h2 class="display-1 text-uppercase">Moda</h2>
                  <div class="caption-description"><h3>Tak wiele możliwości</h3>
<p>Ubierz swoje życie w kolory naszej pasji modowej</p></div>
                </figcaption>
                          </figure>
          </a>
        </li>
              <li class="carousel-item " role="option" aria-hidden="true">
          <a href="">
            <figure>
              <img src="http://localhost:8080/modules/ps_imageslider/images/eec225aaa48add30f0f2782a62f1645e651e0a01_F31D6478-62F1-494D-AB4F-DC6C71974FF6.PNG" alt="Trendy" loading="lazy" width="1110" height="340">
                              <figcaption class="caption">
                  <h2 class="display-1 text-uppercase">Trendy</h2>
                  <div class="caption-description"><h3>Magia kreatywności</h3>
<p>Elegancja w każdym szwie, styl w każdym kroku.</p></div>
                </figcaption>
                          </figure>
          </a>
        </li>
          </ul>
    <div class="direction" aria-label="Przyciski karuzeli">
      <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" aria-label="Poprzedni">
        <span class="icon-prev hidden-xs" aria-hidden="true">
          <i class="material-icons">&#xE5CB;</i>
        </span>
      </a>
      <a class="right carousel-control" href="#carousel" role="button" data-slide="next" aria-label="Następny">
        <span class="icon-next" aria-hidden="true">
          <i class="material-icons">&#xE5CC;</i>
        </span>
      </a>
    </div>
  </div>
<?php }
}
