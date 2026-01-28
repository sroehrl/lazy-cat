<?php

namespace Config\Transformers;

use Neoan\Enums\Direction;
use Neoan\Model\Interfaces\Transformation;

class JsonTransformation implements Transformation
{

    public function __invoke(array $inputOutput, Direction $direction, string $property): array
    {
        if($direction === Direction::IN && !empty($inputOutput[$property])) {
            $inputOutput[$property] = '=' . $inputOutput[$property];
        }
        if($direction === Direction::OUT && !empty($inputOutput[$property])) {
            $inputOutput[$property] = json_decode($inputOutput[$property], true);
        }
        return $inputOutput;
    }
}