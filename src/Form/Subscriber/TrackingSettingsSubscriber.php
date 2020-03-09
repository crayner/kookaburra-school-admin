<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 5/03/2020
 * Time: 14:43
 */

namespace Kookaburra\SchoolAdmin\Form\Subscriber;

use App\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TrackingSettingsSubscriber
 * @package Kookaburra\SchoolAdmin\Form\Subscriber
 */
class TrackingSettingsSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * TrackingSettingsSubscriber constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => ['buildAssessmentSettings'],
        ];
    }

    /**
     * buildAssessmentSettings
     * @param PostSetDataEvent $event
     */
    public function buildAssessmentSettings(PostSetDataEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        if ($this->prefix === 'external') {

            $ext = $form->get('dataPoints');

            foreach ($data->getDataPoints()->toArray() as $q => $item) {
                $ext->get($q)->add('yearGroupList', EntityType::class,
                    [
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                        'label' => $item['label'],
                        'choice_label' => 'name',
                        'translation_domain' => false,
                        'choice_translation_domain' => false,
                        'class' => YearGroup::class,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('g')
                                ->orderBy('g.sequenceNumber', 'ASC');
                        },
                    ]
                );
            }
        } else {
            $ext = $form->get('dataPoints');

            foreach ($data->getDataPoints()->toArray() as $q => $item) {
                $ext->get($q)->add('yearGroupList', EntityType::class,
                    [
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                        'label' => $item['label'],
                        'choice_label' => 'name',
                        'translation_domain' => false,
                        'choice_translation_domain' => false,
                        'class' => YearGroup::class,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('g')
                                ->orderBy('g.sequenceNumber', 'ASC');
                        },
                    ]
                );
            }
        }
    }
}