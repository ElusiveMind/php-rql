<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class BinaryOp extends ValuedQuery
{
    public function __construct($termType, $value, $other)
    {
        $this->termType = $termType;

        $this->setPositionalArg(0, $this->nativeToDatum($value));
        $this->setPositionalArg(1, $this->nativeToDatum($other));
    }

    protected function getTermType(): TermTermType
    {
        return $this->termType;
    }

    private $termType;
}
