<?php

namespace Drupal\faq\Plugin\Block;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Faq by taxonomy' block.
 *
 * @Block(
 *  id = "faq_by_taxonomy",
 *  admin_label = @Translation("faq by taxonomy"),
 * )
 */

class FaqByTaxonomy extends BlockBase{

  const CATEGORY_VID = 'personal_details';
  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#theme' => 'block_container',
      '#title' => '',
      '#data' => $this->selectContent(),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  private function selectContent(){
    $categories = $this->getCategories();
    return array(
      'categories' => $categories
    );
  }

  private function getCategories(){
    $categoriesData = [];

    $categories = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree(self::CATEGORY_VID);


    foreach ($categories as $key => $value) {
      if (!$value->depth) {
        $term = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->load($value->tid);

          $categoriesData[$term->id() ]= array(
            'name'    => self::getFieldValue( $term, 'name' ),
            'icon'   => ! empty( $term->get( 'field_icon' )->entity ) ? Xss::filter( file_create_url( $term->get( 'field_icon' )->entity->getFileUri() ) ) : '',
          'alt_img'  => ! empty( $term->get( 'field_icon' )->entity ) ? Xss::filter( $term->get( 'field_icon' )->alt ) : '',
            'related_faq' => $this->getRelatedFaq($term->id())
          );
        }
      }
    return $categoriesData;

  }

   	public static function getFieldValue($node, $field) {
    if(empty($node))
      return '';
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if($node->hasField($field))
      if ($node->hasTranslation($langcode) && !$node->getTranslation($langcode)->get($field)->isEmpty()) {

        $text = $node->getTranslation($langcode)->get($field)->getString();

        return $text;
      }
    return '';
  }

  private function getRelatedFaq( $termId):array {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'faq')
      ->condition('field_related_taxonomy', $termId)
      ->sort('field_weight', 'DESC');
    $nids = $query->execute();
    return  Node::loadMultiple($nids);

  }



}
