<!-- Preloader Area Start -->
         <div class="preloader">
            <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
               <path id="preloaderSvg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
            </svg>

            <div class="preloader-heading">
               <div class="load-text">
                  <?php
                     $loadingString = mb_str_split($lang->getTranslationByShortcode('loading-text', $translation));

                     // Split the string into an array of characters
                     foreach ($loadingString as $loadingCharacter) {
                        ?><span><?=$loadingCharacter?></span><?php
                     }
                  ?>
               </div>
            </div>
         </div>
         <!-- Preloader Area End -->

         <!-- start: Back To Top -->
         <div class="progress-wrap" id="scrollUp">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
               <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
         </div>
         <!-- end: Back To Top -->