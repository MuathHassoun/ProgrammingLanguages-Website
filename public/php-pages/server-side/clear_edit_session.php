<?php
$editKeys = [
  'editLanguageName', 'editLanguageLogo', 'editImageName',
  'editLanguageImage', 'editDefinition',   'editDescription',
  'edit_full_article', 'edit_list_points', 'editEasyToLearn',
  'editWebDev', 'editMobileDev', 'editGameDev', 'editAiMl',
  'editPerformance', 'editObjectOriented','editCommunitySupport',
  'editMarketDemand', 'editSyntaxSimplicity', 'editBackendDev',
  'editFrontendDev', 'edit-documentation', 'editVideoEmbed',
  'editCompilerUrl', 'otherResourcesEdit'
];

foreach ($editKeys as $k) {
  unset($_SESSION[$k]);
}
echo "<script>console.log('session values cleared');</script>";
