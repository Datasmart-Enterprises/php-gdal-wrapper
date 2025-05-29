<?php
/**
 * This file is part of the GDAL package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7
 *
 * @license GPL License
 */

namespace Datasmart\GDAL;

use Datasmart\GDAL\ogrinfo\Options;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Implements `ogrinfo` function.
 *
 * @author Jonathan Beliën <jbe@geo6.be>
 *
 * @link   http://www.gdal.org/ogrinfo.html
 */
class ogrinfo
{
    /**
     * @param string   $source Datasource.
     * @param string[] $layers Layers from datasource (optional).
     *
     * @return void
     */
    public function __construct(
        private readonly string  $source,
        private readonly array   $layers,
        private readonly Options $options,
        private string           $command = ''
    )
    {

        $this->assembleCommand();
    }

    /**
     * @param string $name  Option name.
     * @param mixed  $value Option value.
     *
     * @return void
     */
    public function setOption(string $name, mixed $value = true): void
    {
        $this->options->{$name} = $value;

        $this->assembleCommand();
    }

    /**
     * @return void
     */
    private function assembleCommand(): void
    {
        $options = '';
        if ($this->options->help === true) {
            $options .= ' --help';
        }
        if ($this->options->helpGeneral === true) {
            $options .= ' --help-general';
        }
        if (!empty($this->options->if)) {
            $options .= sprintf(
                ' -if %s',
                escapeshellarg($this->options->if->value)
            );
        }
        if ($this->options->json === true) {
            $options .= ' -json';
        }
        if ($this->options->ro === true) {
            $options .= ' -ro';
        }
        if ($this->options->features === true) {
            $options .= ' -features';
        }
        if (!empty($this->options->limit)) {
            $options .= sprintf(
                ' -limit %s',
                escapeshellarg((string)$this->options->limit)
            );
        }

        if ($this->options->q === true) {
            $options .= ' -q';
        }
        if (!empty($this->options->where)) {
            $options .= sprintf(
                ' -where %s',
                escapeshellarg($this->options->where)
            );
        }
        if (!empty($this->options->dialect)) {
            $options .= sprintf(
                ' -dialect %s',
                escapeshellarg($this->options->dialect->name)
            );
        }
        if (!empty($this->options->spat)) {
            $options .= sprintf(
                ' -spat %s',
                escapeshellarg($this->options->spat->getString())
            );
        }
        if (!empty($this->options->geomfield)) {
            $options .= sprintf(
                ' -geomfield %s',
                escapeshellarg($this->options->geomfield)
            );
        }
        if (!empty($this->options->fielddomain)) {
            $options .= sprintf(
                ' -fielddomain %s',
                escapeshellarg($this->options->fielddomain)
            );
        }
        if (!empty($this->options->fid)) {
            $options .= sprintf(
                ' -fid %s',
                $this->options->fid
            );
        }
        if (!empty($this->options->sql)) {
            $options .= sprintf(
                ' -sql %s',
                escapeshellarg($this->options->sql)
            );
        }
        if (!empty($this->options->dialect)) {
            $options .= sprintf(
                ' -dialect %s',
                escapeshellarg($this->options->dialect)
            );
        }
        if ($this->options->al === true) {
            $options .= ' -al';
        }
        if ($this->options->rl === true && empty($this->options->sql)) {
            $options .= ' -rl';
        }
        if ($this->options->so === true) {
            $options .= ' -so';
        }
        if ($this->options->fields === true) {
            $options .= ' -fields=YES';
        } elseif ($this->options->fields === false) {
            $options .= ' -fields=NO';
        }

        if (
            !empty($this->options->geom) &&
            in_array($this->options->geom, Options::VALID_GEOMETRY_DUMP_TYPES, true)
        ) {
            $options .= sprintf(
                ' -geom %s',
                escapeshellarg($this->options->geom)
            );
        }
        if ($this->options->formats === true) {
            $options .= ' --formats';
        }
        if ($this->options->nomd === true) {
            $options .= ' -nomd';
        }
        if ($this->options->listmdd === true) {
            $options .= ' -listmdd';
        }
        if (!empty($this->options->mdd)) {
            $options .= sprintf(
                ' -mdd %s',
                escapeshellarg($this->options->mdd)
            );
        }
        if ($this->options->nocount === true) {
            $options .= ' -nocount';
        }
        if ($this->options->noextent === true) {
            $options .= ' -noextent';
        }
        if ($this->options->extend3D === true) {
            $options .= ' -extend3D';
        }
        if ($this->options->nogeomtype === true) {
            $options .= ' -nogeomtype';
        }

        if (!empty($this->options->oo)) {
            foreach ($this->options->oo as $name => $value) {
                $options .= sprintf(
                    ' -oo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }

        if (
            !empty($this->options->wkt_format) &&
            in_array($this->options->wkt_format, Options::VALID_WKT_FORMATS, true)
        ) {
            $options .= sprintf(
                ' -wkt_format %s',
                escapeshellarg($this->options->wkt_format)
            );
        }

        $this->command = sprintf(
            'ogrinfo %s %s %s',
            $options,
            preg_match('/^[a-z]{2,}:/i', $this->source) === 1 ? $this->source : escapeshellarg($this->source),
            implode(' ', $this->layers)
        );

    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param callable|null $callback
     * @param array         $env      An array of additional env vars to set when running the process
     *
     * @throws ProcessFailedException if the process is not successful.
     *
     * @return string
     */
    public function run(?callable $callback = null, array $env = []): string
    {
        $process = Process::fromShellCommandline($this->getCommand());
        $process->mustRun($callback, $env);
        $process->wait();

        return $process->getOutput();
    }
}
