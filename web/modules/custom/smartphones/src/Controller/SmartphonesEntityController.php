<?php

namespace Drupal\smartphones\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\smartphones\Entity\SmartphonesEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SmartphonesEntityController.
 *
 *  Returns responses for Smartphones entity routes.
 */
class SmartphonesEntityController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new SmartphonesEntityController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Smartphones entity revision.
   *
   * @param int $smartphones_entity_revision
   *   The Smartphones entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($smartphones_entity_revision) {
    $smartphones_entity = $this->entityTypeManager()->getStorage('smartphones_entity')
      ->loadRevision($smartphones_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('smartphones_entity');

    return $view_builder->view($smartphones_entity);
  }

  /**
   * Page title callback for a Smartphones entity revision.
   *
   * @param int $smartphones_entity_revision
   *   The Smartphones entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($smartphones_entity_revision) {
    $smartphones_entity = $this->entityTypeManager()->getStorage('smartphones_entity')
      ->loadRevision($smartphones_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $smartphones_entity->label(),
      '%date' => $this->dateFormatter->format($smartphones_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Smartphones entity.
   *
   * @param \Drupal\smartphones\Entity\SmartphonesEntityInterface $smartphones_entity
   *   A Smartphones entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SmartphonesEntityInterface $smartphones_entity) {
    $account = $this->currentUser();
    $smartphones_entity_storage = $this->entityTypeManager()->getStorage('smartphones_entity');

    $langcode = $smartphones_entity->language()->getId();
    $langname = $smartphones_entity->language()->getName();
    $languages = $smartphones_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $smartphones_entity->label()]) : $this->t('Revisions for %title', ['%title' => $smartphones_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all smartphones entity revisions") || $account->hasPermission('administer smartphones entity entities')));
    $delete_permission = (($account->hasPermission("delete all smartphones entity revisions") || $account->hasPermission('administer smartphones entity entities')));

    $rows = [];

    $vids = $smartphones_entity_storage->revisionIds($smartphones_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\smartphones\SmartphonesEntityInterface $revision */
      $revision = $smartphones_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $smartphones_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.smartphones_entity.revision', [
            'smartphones_entity' => $smartphones_entity->id(),
            'smartphones_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $smartphones_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.smartphones_entity.translation_revert', [
                'smartphones_entity' => $smartphones_entity->id(),
                'smartphones_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.smartphones_entity.revision_revert', [
                'smartphones_entity' => $smartphones_entity->id(),
                'smartphones_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.smartphones_entity.revision_delete', [
                'smartphones_entity' => $smartphones_entity->id(),
                'smartphones_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['smartphones_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
