

         <!-- HEADER START -->
         <header class="tj-header-area header-absolute">
            <div class="container">
               <div class="row">
                  <div class="col-12 d-flex flex-wrap align-items-center">
                     <div class="logo-box">
                        <a href="<?=_DOMINIO_.$_SESSION['lang']?>/" aria-label="<?=$lang->getTranslationByShortcode('menu-item-1', $translation)?>">
                           <img src="<?=_DOMINIO_?>assets/img/logo/logo.webp" loading="lazy" alt="" />
                        </a>
                     </div>

                     <div class="header-info-list d-none d-md-inline-block">
                        <ul class="ul-reset">
                           <li><a href="mailto:<?=_MAIL_?>" arial-label="Mail <?=_MAIL_?>"><?=_MAIL_?></a></li>
                        </ul>
                     </div>
                     <div class="header-menu" id="headerMenu">
                        <nav>
                           <ul>
                              <li class="<?=(isset($_REQUEST['mod_id']) && isset($_REQUEST['mod_id']) == '') ? 'current-menu-ancestor' : ''?>">
                                 <a class="<?=(isset($_REQUEST['mod_id']) && isset($_REQUEST['mod_id']) == '') ? 'current-menu-item' : ''?>" href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-1', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-1', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-1', $translation)?></a>
                              </li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-2', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-2', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-2', $translation)?></a></li>
                              <?php
                                 //If has skills show menu.
                                 if(isset($skillData->skills) && count($skillData->skills) > 0){
                                    ?><li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-3', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-3', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-3', $translation)?></a></li><?php
                                 }
                              ?>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-4', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-4', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-4', $translation)?></a></li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-5', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-5', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-5', $translation)?></a></li>
                              <?php
                                 if(isset($languages) && count($languages) > 0){
                                    foreach($languages as $language){
                                       if($language->status == "active" && $language->slug != $_SESSION['lang']){
                                          ?>
                                             <li>
                                                <a href="<?=_DOMINIO_.$language->slug?>/" aria-label="<?=$language->name?>">
                                                   <img src="<?=_DOMINIO_.$language->icon?>" loading="lazy" alt="<?=$language->name?>" width="20" />
                                                </a>
                                             </li>
                                          <?php
                                       }
                                    }
                                 }
                              ?>
                           </ul>
                        </nav>
                     </div>
                     <div class="mobile-menu d-lg-none"></div>
                     <div class="header-button">
                        <a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-7', $translation));?>" class="btn tj-btn-primary" aria-label="<?=$lang->getTranslationByShortcode('menu-item-7', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-7', $translation)?></a>
                     </div>
                     <div class="menu-bar d-lg-none">
                        <button>
                           <span></span>
                           <span></span>
                           <span></span>
                           <span></span>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         <header class="tj-header-area header-2 header-sticky sticky-out">
            <div class="container">
               <div class="row">
                  <div class="col-12 d-flex flex-wrap align-items-center">
                     <div class="logo-box">
                        <a href="<?=_DOMINIO_.$_SESSION['lang']?>/" aria-label="<?=$lang->getTranslationByShortcode('menu-item-1', $translation)?>">
                           <img src="<?=_DOMINIO_?>assets/img/logo/logo.webp" loading="lazy" alt="" />
                        </a>
                     </div>
                     <div class="header-info-list d-none d-md-inline-block">
                        <ul class="ul-reset">
                           <li><a href="mailto:<?=_MAIL_?>"><?=_MAIL_?></a></li>
                        </ul>
                     </div>
                     <div class="header-menu">
                        <nav>
                           <ul>
                              <li class="<?=(isset($_REQUEST['mod_id']) && isset($_REQUEST['mod_id']) == '') ? 'current-menu-ancestor' : ''?>">
                                 <a class="<?=(isset($_REQUEST['mod_id']) && isset($_REQUEST['mod_id']) == '') ? 'current-menu-item' : ''?>" href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-1', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-1', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-1', $translation)?></a>
                              </li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-2', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-2', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-2', $translation)?></a></li>
                              <?php
                                 //If has skills show menu.
                                 if(isset($skillData->skills) && count($skillData->skills) > 0){
                                    ?><li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-3', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-3', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-3', $translation)?></a></li><?php
                                 }
                              ?>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-4', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-4', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-4', $translation)?></a></li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-5', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-5', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-5', $translation)?></a></li>
                              <?php
                                 if(isset($languages) && count($languages) > 0){
                                    foreach($languages as $language){
                                       if($language->status == "active" && $language->slug != $_SESSION['lang']){
                                          ?>
                                             <li>
                                                <a href="<?=_DOMINIO_.$language->slug?>/" aria-label="<?=$language->name?>">
                                                   <img src="<?=_DOMINIO_.$language->icon?>" loading="lazy" alt="<?=$language->name?>" width="20" />
                                                </a>
                                             </li>
                                          <?php
                                       }
                                    }
                                 }
                              ?>
                           </ul>
                        </nav>
                     </div>
                     <div class="mobile-menu d-lg-none"></div>
                     <div class="header-button">
                        <a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-7', $translation));?>" class="btn tj-btn-primary" aria-label="<?=$lang->getTranslationByShortcode('menu-item-7', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-7', $translation)?></a>
                     </div>
                     <div class="menu-bar d-lg-none">
                        <button>
                           <span></span>
                           <span></span>
                           <span></span>
                           <span></span>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         <!-- HEADER END -->