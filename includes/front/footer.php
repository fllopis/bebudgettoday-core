      
         <!-- FOOTER AREA START -->
         <footer class="tj-footer-area">
            <div class="container">
               <div class="row">
                  <div class="col-md-12 text-center">
                     <div class="footer-logo-box">
                        <a href="<?=_DOMINIO_.$_SESSION['lang']?>/" aria-label="<?=$lang->getTranslationByShortcode('menu-item-1', $translation)?>">
                           <img src="<?=_DOMINIO_?>assets/img/logo/logo.webp" loading="lazy" alt="" />
                        </a>
                     </div>
                     <div class="footer-menu">
                        <nav>
                           <ul>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-2', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-2', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-2', $translation)?></a></li>
                              <?php
                                 //If has skills show menu.
                                 if(isset($skillData->skills) && count($skillData->skills) > 0){
                                    ?><li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-3', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-3', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-3', $translation)?></a></li></li><?php
                                 }
                              ?>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-4', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-4', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-4', $translation)?></a></li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-5', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-5', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-5', $translation)?></a></li>
                              <li><a href="<?=_DOMINIO_.$_SESSION['lang']?>/#<?=$tools->urlAmigable($lang->getTranslationByShortcode('menu-item-7', $translation));?>" aria-label="<?=$lang->getTranslationByShortcode('menu-item-7', $translation)?>"><?=$lang->getTranslationByShortcode('menu-item-7', $translation)?></a></li>
                           </ul>
                        </nav>
                     </div>
                     <div class="copy-text">
                        <p>
                           &copy; <?=date("Y")?> <?=$lang->getTranslationByShortcode('footer-copy', $translation)?>
                           <?php
                              if(isset($copyLinks) && count($copyLinks) > 0){
                                 echo " · ";

                                 foreach($copyLinks as $key => $copyLink){
                                    ?>
                                       <a href="<?=_DOMINIO_.$_SESSION['lang']."/".$copyLink->slug?>/" aria-label="<?=$copyLink->h1?>"><?=$copyLink->h1?></a> 
                                    <?php

                                    if(count($copyLinks) > ($key + 1)){
                                       echo " · ";
                                    }
                                 }
                              }
                           ?>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </footer>
         <!-- FOOTER AREA END -->
