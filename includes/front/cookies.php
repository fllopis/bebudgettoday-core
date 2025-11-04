<div id="cookie-banner" style="display: none;">
  <div class="cookie-container">
    <?php
        $cookiesContent = $lang->getTranslationByShortcode('cookies-content', $translation);

        //Getting cookies policy slug
        $cookiesPolicyUrl = "";
        $cookiesInternalName = "";
        if(isset($copyLinks) && count($copyLinks) > 0){
            foreach($copyLinks as $keyC => $copyLink){
                $cookiesPolicyInternalTitle = $tools->urlAmigable($copyLink->internal_title);

                if($cookiesPolicyInternalTitle == 'politica-de-cookies'){
                    $cookiesInternalName = $copyLink->internal_title;
                    $cookiesPolicyUrl = _DOMINIO_.$_SESSION['lang']."/".$copyLink->slug."/";
                }
            }
        }

        $cookiesContent = $lang->replaceArg($cookiesContent, 'start-link-cookies', '<a href="'.$cookiesPolicyUrl.'" target="_blank" arial-label="'.$cookiesInternalName.'">');
        $cookiesContent = $lang->replaceArg($cookiesContent, 'end-link-cookies', '</a>');
    ?>
    <p><?=$cookiesContent?></p>
    <button id="accept-cookies" class="btn tj-btn-primary"><?=$lang->getTranslationByShortcode('cookies-button', $translation)?></button>
    <button id="reject-cookies" class="btn-cookie-reject"><?=$lang->getTranslationByShortcode('cookies-button-reject', $translation)?></button>
  </div>
</div>