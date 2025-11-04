<main class="site-content" id="content">
    <!-- SUBHEADER: Breadcrumb -->
    <section class="breadcrumb_area" data-bg-image="<?=_DOMINIO_?>/images/header-default.jpg"
        data-bg-color="#140C1C">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb_content d-flex flex-column align-items-center">
                        <h1 class="title wow fadeInUp" data-wow-delay=".3s"><?=(isset($pageData->h1)) ? $pageData->h1 : ''?></h1>
                        <div class="breadcrumb_navigation wow fadeInUp" data-wow-delay=".5s">
                            <span>
                                <a href="<?=_DOMINIO_.$_SESSION['lang']?>/"><?=$lang->getTranslationByShortcode('menu-item-1', $translation)?></a>
                            </span>
                            <i class="far fa-long-arrow-right"></i>
                            <span class="current-item"><?=(isset($pageData->h1)) ? $pageData->h1 : ''?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CONTENT -->
    <section class="service-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?=(isset($pageData->content)) ? $pageData->content : ''?>
                </div>
            </div>
        </div>
    </section>
    <!-- end: Service Area -->

</main>