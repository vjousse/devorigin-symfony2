<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo $view['assets']->getUrl('css/style.css') ?>" rel="stylesheet" type="text/css" />
    <title><?php $view['slots']->output('title', 'Création de sites Internet - Le Mans') ?></title>
  </head>
  <body>
    <div id="templatemo_header_wrapper">
      <div id="templatemo_header">

        <div id="title"><a href="/">DEVORIGIN</a>
          <span>Création de sites Internet - symfony<br />Le Mans</span>
        </div>

        <div id="twitter"><a href="http://twitter.com/vjousse">&nbsp;</a></div>

      </div> <!-- end of header -->

    </div> <!-- end of header wrapper -->
    <div id="templatemo_menu_wrapper">

      <div id="templatemo_menu">

        <ul>
          <li><a href="#" class="current"><span></span>Accueil</a></li>
          <li><a href="#"><span></span>Réalisations</a></li>
          <li><a href="#"><span></span>CV</a></li>
          <li><a href="#"><span></span>Blog</a></li>
          <li><a href="#"><span></span>Contact</a></li>
        </ul>
      </div> <!-- end of menu -->
    </div> <!-- end of menu wrapper -->


    <div id="templatemo_content_wrapper">

      <div id="templatemo_content">

        <div class="section_w620 fl margin_right_50">

          <?php $view->slots->output('_content') ?>

          <div class="margin_bottom_40"></div>
          <div class="cleaner"></div>
        </div>


        <div class="section_w250 fr">

          <div class="section_w250_title news_title">
            Dernière nouvelles
          </div>

          <div class="w250_content">

            <div class="latest_news">
              <div class="header_04">6 Janvier 2010</div>
              <div class="header_02"><a href="http://www.arnage.fr">Site de la ville d'Arnage</a></div>
              <p>Le site de la ville d'Arnage est désormais <a href="http://www.arnage.fr">en ligne</a> !</p>
            </div>

            <div class="margin_bottom_20"></div>

            <div class="latest_news">
              <div class="header_04">20 Novembre 2009</div>
              <div class="header_02"><a href="http://www.ville-rouillon.fr">Relooking du site de Rouillon</a></div>
              <p>Le site de la ville de Rouillon est maintenant aux couleurs de la nouvelle charte graphique. N'hésitez pas à aller faire un <a href="http://www.ville-rouillon.fr">tour sur le site</a> pour voir le nouveau design.</p>
            </div>

            <div class="margin_bottom_20"></div>

            <div class="latest_news">
              <div class="header_04">6 Novembre 2009</div>
              <div class="header_02"><a href="http://inscriptions.24heuresvelo.fr/">Inscriptions des 24 heures vélo</a></div>
              <p>Les <a href="http://inscriptions.24heuresvelo.fr/">inscriptions pour les 24 heures vélo du Mans</a> 2010 sont maintenant ouvertes.</p>
            </div>

          </div>

          <div class="margin_bottom_20"></div>


          <div class="margin_bottom_20"></div>
        </div>
      </div> <!-- end of content -->

    </div> <!-- end of content wrapper -->

    
    <div id="templatemo_footer_wrapper">

      <div id="templatemo_footer">

        <a href="http://www.devorigin.fr">DevOrigin</a>, SIRET : 510 117 864 000 15 |
        Design par <a href="http://www.templatemo.com/page/1">Free CSS Templates</a>

        <div class="margin_bottom_20"></div>

        <a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" /></a>

      </div> <!-- end of footer -->

    </div> <!-- end of footer wrapper -->

  </body>
</html>
