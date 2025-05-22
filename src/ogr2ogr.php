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

declare(strict_types=1);

namespace Datasmart\GDAL;

use Datasmart\GDAL\ogr2ogr\Options;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Implements `ogr2ogr` function.
 *
 * @author Jonathan Beliën <jbe@geo6.be>
 *
 * @link   http://www.gdal.org/ogr2ogr.html
 */
class ogr2ogr
{
    /**
     * @var string
     */
    private string $command = '';

    private ogr2ogr\Options $options;

    /**
     * @var string
     */
    private string $destination;

    /**
     * @var string
     */
    private string $source;

    /**
     * @var string[]
     */
    private array $layers;

    /**
     * @param string   $destination Destination datasource.
     * @param string   $source      Source datasource.
     * @param string[] $layers      Layers from source datasource (optional).
     *
     * @return void
     */
    public function __construct(string $destination, string $source, string|array $layers = [])
    {
        $this->destination = $destination;
        $this->source = $source;
        $this->layers = (is_string($layers) ? [$layers] : $layers);
        $this->options = new ogr2ogr\Options();

        $this->assembleCommand();
    }

    /**
     * @param string $name  Option name.
     * @param true|mixed $value Option value.
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
        if ($this->options->skipfailures === true) {
            $options .= ' -skipfailures';
        }
        if ($this->options->skipinvalid === true) {
            $options .= ' -skipinvalid';
        }
        if ($this->options->append === true && $this->options->upsert === false) {
            $options .= ' -append';
        }
        if ($this->options->upsert === true && $this->options->append === false) {
            $options .= ' -upsert';
        }
        if ($this->options->update === true) {
            $options .= ' -update';
        }
        if (!empty($this->options->dateTimeTo)) {
            $options .= sprintf(
                ' -dateTimeTo %s',
                escapeshellarg($this->options->dateTimeTo)
            );
        }
        if (!empty($this->options->select)) {
            $options .= sprintf(
                ' -select %s',
                escapeshellarg($this->options->select)
            );
        }
        if (!empty($this->options->where)) {
            $options .= sprintf(
                ' -where %s',
                escapeshellarg($this->options->where)
            );
        }
        if ($this->options->progress === true) {
            $options .= ' -progress';
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
                escapeshellarg($this->options->dialect->name)
            );
        }
        if ($this->options->preserve_fid === true) {
            $options .= ' -preserve_fid';
        }
        if (!empty($this->options->fid)) {
            $options .= sprintf(
                ' -fid %s',
                escapeshellarg($this->options->fid)
            );
        }
        if (!empty($this->options->limit)) {
            $options .= sprintf(
                ' -limit %s',
                escapeshellarg((string)$this->options->limit)
            );
        }
        if (!empty($this->options->spat)) {
            $options .= sprintf(
                ' -spat %s',
                escapeshellarg($this->options->spat->getString())
            );
        }
        if (!empty($this->options->spat_srs)) {
            $options .= sprintf(
                ' -spat_srs %s',
                escapeshellarg($this->options->spat_srs)
            );
        }
        if (!empty($this->options->geomfield)) {
            $options .= sprintf(
                ' -geomfield %s',
                escapeshellarg($this->options->geomfield)
            );
        }
        if (!empty($this->options->a_srs)) {
            $options .= sprintf(
                ' -a_srs %s',
                escapeshellarg($this->options->a_srs)
            );
        }
        if (!empty($this->options->a_coord_epoch)) {
            $options .= sprintf(
                ' -a_coord_epoch %s',
                escapeshellarg($this->options->a_coord_epoch)
            );
        }
        if (!empty($this->options->t_srs)) {
            $options .= sprintf(
                ' -t_srs %s',
                escapeshellarg($this->options->t_srs)
            );
        }
        if (!empty($this->options->t_coord_epoch)) {
            $options .= sprintf(
                ' -t_coord_epoch %s',
                escapeshellarg($this->options->t_coord_epoch)
            );
        }
        if (!empty($this->options->s_srs)) {
            $options .= sprintf(
                ' -s_srs %s',
                escapeshellarg($this->options->s_srs)
            );
        }
        if (!empty($this->options->s_coord_epoch)) {
            $options .= sprintf(
                ' -s_coord_epoch %s',
                escapeshellarg($this->options->s_coord_epoch)
            );
        }
        if (!empty($this->options->xyRes)) {
            $options .= sprintf(
                ' -xyRes %s',
                escapeshellarg($this->options->xyRes)
            );
        }
        if (!empty($this->options->zRes)) {
            $options .= sprintf(
                ' -zRes %s',
                escapeshellarg($this->options->zRes)
            );
        }
        if (!empty($this->options->mRes)) {
            $options .= sprintf(
                ' -mRes %s',
                escapeshellarg($this->options->mRes)
            );
        }
        if ($this->options->unsetCoordPrecision === true) {
            $options .= ' -unsetCoordPrecision';
        }
        if (!empty($this->options->ct)) {
            $options .= sprintf(
                ' -ct %s',
                escapeshellarg($this->options->ct)
            );
        }
        if (!empty($this->options->ct_opt)) {
            foreach ($this->options->ct_opt as $name => $value) {
                $options .= sprintf(
                    ' -ct_opt %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->options->if)) {
            $options .= sprintf(
                ' -if %s',
                escapeshellarg($this->options->if->value)
            );
        }
        if (!empty($this->options->f)) {
            $options .= sprintf(
                ' -f %s',
                escapeshellarg($this->options->f->value)
            );
        }
        if ($this->options->overwrite === true) {
            $options .= ' -overwrite';
        }
        if ($this->options->ds_transaction === true) {
            $options .= ' -ds_transaction';
        }
        if ($this->options->makevalid === true) {
            $options .= ' -makevalid';
        }
        if (!empty($this->options->nln)) {
            $options .= sprintf(
                ' -nln %s',
                escapeshellarg($this->options->nln)
            );
        }
        if (!empty($this->options->nlt)) {
            $options .= $this->options->nlt->getString();
        }
        if (!empty($this->options->dim)) {
            $options .= sprintf(
                ' -dim %s',
                escapeshellarg($this->options->dim->name)
            );
        }
        if (!empty($this->options->gt)) {
            $options .= sprintf(
                ' -gt %s',
                escapeshellarg((string)$this->options->gt)
            );
        }
        if (!empty($this->options->clipsrc)) {
            $options .= sprintf(
                ' -clipsrc %s',
                escapeshellarg($this->options->clipsrc)
            );
        }
        if (!empty($this->options->clipsrcsql)) {
            $options .= sprintf(
                ' -clipsrcsql %s',
                escapeshellarg($this->options->clipsrcsql)
            );
        }
        if (!empty($this->options->clipsrclayer)) {
            $options .= sprintf(
                ' -clipsrclayer %s',
                escapeshellarg($this->options->clipsrclayer)
            );
        }
        if (!empty($this->options->clipsrcwhere)) {
            $options .= sprintf(
                ' -clipsrcwhere %s',
                escapeshellarg($this->options->clipsrcwhere)
            );
        }
        if (!empty($this->options->clipdst)) {
            $options .= sprintf(
                ' -clipdst %s',
                escapeshellarg($this->options->clipdst)
            );
        }
        if (!empty($this->options->clipdstsql)) {
            $options .= sprintf(
                ' -clipdstsql %s',
                escapeshellarg($this->options->clipdstsql)
            );
        }
        if (!empty($this->options->clipdstlayer)) {
            $options .= sprintf(
                ' -clipdstlayer %s',
                escapeshellarg($this->options->clipdstlayer)
            );
        }
        if (!empty($this->options->clipdstwhere)) {
            $options .= sprintf(
                ' -clipdstwhere %s',
                escapeshellarg($this->options->clipdstwhere)
            );
        }
        if ($this->options->wrapdateline === true) {
            $options .= ' -wrapdatakline';
        }
        if (!empty($this->options->datelineoffset)) {
            $options .= sprintf(
                ' -datelineoffset %s',
                escapeshellarg((string)$this->options->datelineoffset)
            );
        }
        if (!empty($this->options->simplify)) {
            $options .= sprintf(
                ' -simplify %s',
                escapeshellarg((string)$this->options->simplify)
            );
        }
        if (!empty($this->options->segmentize)) {
            $options .= sprintf(
                ' -segmentize %s',
                escapeshellarg((string)$this->options->segmentize)
            );
        }
        if ($this->options->addfields === true) {
            $options .= ' -addfields';
        }
        if ($this->options->unsetFid === true) {
            $options .= ' -unsetFid';
        }
        if ($this->options->relaxedFieldNameMatch === true) {
            $options .= ' -relaxedFieldNameMatch';
        }
        if ($this->options->forceNullable === true) {
            $options .= ' -forceNullable';
        }
        if ($this->options->unsetDefault === true) {
            $options .= ' -unsetDefault';
        }
        if (!empty($this->options->fieldTypeToString)) {
            $options .= sprintf(
                ' -fieldTypeToString %s',
                escapeshellarg(implode(',', $this->options->fieldTypeToString))
            );
        }
        if ($this->options->unsetFieldWidth === true) {
            $options .= ' -unsetFieldWidth';
        }
        if (!empty($this->options->mapFieldType)) {
            $options .= sprintf(
                ' -mapFieldType %s',
                escapeshellarg($this->options->mapFieldType)
            );
        }
        if (!empty($this->options->fieldmap)) {
            $options .= sprintf(
                ' -fieldmap %s',
                escapeshellarg($this->options->fieldmap)
            );
        }
        if ($this->options->splitlistfields === true) {
            $options .= ' -splitlistfields';
        }
        if (!empty($this->options->maxsubfields)) {
            $options .= sprintf(
                ' -maxsubfields %s',
                escapeshellarg((string)$this->options->maxsubfields)
            );
        }
        if ($this->options->explodecollections === true) {
            $options .= ' -explodecollections';
        }
        if (!empty($this->options->zfield)) {
            $options .= sprintf(
                ' -zfield %s',
                escapeshellarg($this->options->zfield)
            );
        }
        if (!empty($this->options->gcp)) {
            foreach ($this->options->gcp as $controlPoint) {
                $options .= sprintf(
                    ' -gcp %s',
                    escapeshellarg($controlPoint->getString())
                );
            }
        }
        if (is_int($this->options->order) && in_array($this->options->order, Options::VALID_POLYNOMIAL_ORDERS, true)) {
            $options .= sprintf(
                ' -order %s',
                escapeshellarg((string)$this->options->order)
            );
        }
        if ($this->options->tps === true) {
            $options .= ' -tps';
        }
        if ($this->options->emptyStrAsNull === true) {
            $options .= ' -emptyStrAsNull';
        }
        if ($this->options->resolveDomains === true) {
            $options .= ' -resolveDomains';
        }
        if ($this->options->nomd === true) {
            $options .= ' -nomd';
        }
        if (!empty($this->options->mo)) {
            foreach ($this->options->mo as $metadataTag => $metadataValue) {
                $options .= sprintf(
                    ' -mo %s',
                    escapeshellarg(sprintf('%s=%s', $metadataTag, $metadataValue))
                );
            }
        }
        if ($this->options->noNativeData === true) {
            $options .= ' -noNativeData';
        }

        if (!empty($this->options->dsco) && is_array($this->options->dsco)) {
            foreach ($this->options->dsco as $name => $value) {
                $options .= sprintf(
                    ' -dsco %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->options->lco) && is_array($this->options->lco)) {
            foreach ($this->options->lco as $name => $value) {
                $options .= sprintf(
                    ' -lco %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->options->oo) && is_array($this->options->oo)) {
            foreach ($this->options->oo as $name => $value) {
                $options .= sprintf(
                    ' -oo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->options->doo)) {
            foreach ($this->options->doo as $name => $value) {
                $options .= sprintf(
                    ' -doo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }

        $this->command = sprintf(
            'ogr2ogr %s %s %s %s',
            $options,
            preg_match('/^[a-z]{2,}:/i', $this->destination) === 1 ? $this->destination : escapeshellarg($this->destination),
            preg_match('/^[a-z]{2,}:/i', $this->source) === 1 ? $this->source : escapeshellarg($this->source),
            implode(' ', $this->layers)
        );
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param callable|null $callback
     * @param array         $env      An array of additional env vars to set when running the process
     * @throws ProcessFailedException if the process is not successful.
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
