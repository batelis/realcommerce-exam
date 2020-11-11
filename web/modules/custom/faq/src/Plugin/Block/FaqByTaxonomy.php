<?php

namespace Drupal\faq\Plugin\Block;

use Drupal\Core\Block\BlockBase;

class FaqByTaxonomy extends BlockBase{
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['faq_by_taxonomy']['#markup'] = 'Implement FaqByTaxonomy.';
    return $build;
  }



}
