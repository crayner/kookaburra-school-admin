<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 14/01/2020
 * Time: 08:50
 */

namespace Kookaburra\SchoolAdmin\Manager\Hidden;


use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Kookaburra\SchoolAdmin\Pagination\ScaleGradePagination;

class ScaleGradeSort
{
    /**
     * @var ScaleGrade
     */
    private $source;

    /**
     * @var ScaleGrade
     */
    private $target;

    /**
     * @var ScaleGradePagination
     */
    private $pagination;

    /**
     * @var array
     */
    private $details = [];

    /**
     * @var array
     */
    private $content = [];

    /**
     * ScaleGradeSort constructor.
     * @param ScaleGrade $source
     * @param ScaleGrade $target
     * @param ScaleGradePagination $pagination
     */
    public function __construct(ScaleGrade $source, ScaleGrade $target, ScaleGradePagination $pagination)
    {
        $this->source = $source;
        $this->target = $target;
        $this->pagination = $pagination;
        $this->details['status'] = 'success';
        $this->details['errors'] = [];

        if ($this->source->getScale() !== $this->target->getScale())
        {
            $this->details = ErrorMessageHelper::getInvalidInputsMessage($this->details, true);
            return;
        }

        $provider = ProviderFactory::create(ScaleGrade::class);

        $content = $provider->getRepository()->findBy(['scale' => $source->getScale()],['sequenceNumber' => 'ASC']);

        $key = 1;
        $result = [];
        foreach($content as $q => $item)
        {
            if ($item === $source)
                continue;
            if ($item === $target) {
                $source->setSequenceNumber($key++);
                $result[] = $source;
            }
            $item->setSequenceNumber($key++);
            $result[] = $item;
        }
        $this->details = $provider->saveGrades($result, $this->details);
        $this->content = $result;

        if ($this->details['status'] === 'success')
            $this->details = ErrorMessageHelper::getSuccessMessage($this->details, true);
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        if ($this->details['status'] === 'success') {
            $this->details['content'] = $this->pagination->setContent($this->content)->toArray()['content'];
        }
        return $this->details;
    }

    /**
     * Details.
     *
     * @param array $details
     * @return ScaleGradeSort
     */
    public function setDetails(array $details): ScaleGradeSort
    {
        $this->details = $details;
        return $this;
    }
}