<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
    <head>

        <!-- METAS -->
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="google-site-verification" content="afqf_S9nzo66fvcnWsTjAHzPF0GR6cnEO5Xt_cP3hQc" />

        <?php $tools->getMetas();?>

        <!-- FAVICON -->
        <link rel="apple-touch-icon" href="<?=_DOMINIO_?>assets/img/icons/favicon/apple-touch-icon.png" />
        <link rel="shortcut icon" type="image/png" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico" />
        <link rel="icon" type="image/png" sizes="32x32" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=_DOMINIO_?>assets/img/icons/favicon/favicon.ico">

        <!-- HREFLANG -->
         <link rel="alternate" hreflang="x-default" href="<?=_DOMINIO_.$_SESSION['lang']?>/" />
        <?php
            if(isset($languages) && count($languages) > 0){
                foreach($languages as $keyL => $linkLang){
                    echo '<link rel="alternate" hreflang="'.$linkLang->slug.'" href="'._DOMINIO_.$linkLang->slug.'/" />' . "\n\t\t";
                }
            }
        ?>

        <!-- CANONICAL -->
        <link rel="canonical" href="<?=_DOMINIO_.$_SESSION['lang']?>/<?=(isset($_REQUEST['mod']) && $_REQUEST['mod'] != "") ? $_REQUEST['mod'] . '/' : ''?>" />

        <!-- STYLES -->
        <link rel="stylesheet" href="<?=_DOMINIO_?>assets/css/bootstrap.min.css" />

        <script language="javascript">
            var dominio = '<?=_DOMINIO_?>';
            var reCAPTCHA = '<?=_GOOGLE_RECAPTCHA_PUBLIC_KEY_?>';
        </script>

        <!-- GOOGLE reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js?render=<?= _GOOGLE_RECAPTCHA_PUBLIC_KEY_ ?>"></script>
    </head>
    <body <?=(isset($_REQUEST['mod']) && $_REQUEST['mod'] != "") ? 'class="absolute_header"' : '';?>>
        
        <?php include(_INCLUDES_."front/loader.php") ?>

        <?php include(_INCLUDES_."front/header.php") ?>

        <?php $this->app['render']->getPage(); ?>

        <?php include(_INCLUDES_."front/footer.php") ?>

        <?php include(_INCLUDES_."front/cookies.php") ?>

        <!-- JAVASCRIPTS, JQUERYS, CUSTOMS -->
        <script src="<?=_DOMINIO_?>assets/js/jquery.min.js" defer></script>
        <script src="<?=_DOMINIO_?>assets/js/bootstrap.bundle.min.js" defer></script>

        <script src="<?=_DOMINIO_?>assets/js/funks.js?c=<?=time()?>" defer></script>

        <script>
            $(function () {
              $('[data-toggle="tooltip"]').tooltip()
            })            
        </script>
    </body>
</html>
