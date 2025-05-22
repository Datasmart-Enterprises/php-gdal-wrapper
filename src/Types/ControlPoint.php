<?php

namespace Datasmart\GDAL\Types;

class ControlPoint
{
    public function __construct(
        public float $ungeoref_x,
        public float $ungeoref_y,
        public float $georef_x,
        public float $georef_y,
    )
    {
    }

    public function getString(): string
    {
        return $this->ungeoref_x . ' ' . $this->ungeoref_y . ' ' . $this->georef_x . ' ' . $this->georef_y;
    }
}