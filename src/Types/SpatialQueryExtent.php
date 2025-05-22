<?php

namespace Datasmart\GDAL\Types;

class SpatialQueryExtent
{
    public function __construct(
        public float $xmin,
        public float $ymin,
        public float $xmax,
        public float $ymax,
    )
    {
    }

    public function getString(): string
    {
        return $this->xmin . ' ' . $this->ymin . ' ' . $this->xmax . ' ' . $this->ymax;
    }
}