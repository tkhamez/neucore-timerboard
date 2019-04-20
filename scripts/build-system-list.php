<?php
/**
 * 1. get the static data export from https://developers.eveonline.com/resource/resources
 * 2. unpack it
 * 3. set it's path below
 * 4. execute
 */

$pathToSDE = '/path/to/sde';

$systems = iterate($pathToSDE.'/fsd/universe/eve');
$systems = array_merge($systems, iterate($pathToSDE.'/fsd/universe/wormhole'));

$values = [];
foreach ($systems as $system) {
    $values[] = implode(', ', array_map('fixNameAndEscape', $system));
}
$sql = "INSERT INTO systems (name, constellation, region) VALUES \n".
    "(".implode("),\n(", $values) . ")\n";

file_put_contents(__DIR__.'/systems.sql', $sql);

function fixNameAndEscape($name)
{
    // add spaces before camel case, e. g. "SinqLaison" -> "Sinq Laison"
    $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);

    return "'".trim(str_replace(['\\', '"', "'", "\n", "\r"], ['\\\\', '\"', "\\'", ' ', ' '], $name))."'";
}

function iterate($path, $systems = [], SplFileInfo $region = null, SplFileInfo $constellation = null)
{
    $dir = new DirectoryIterator($path);
    foreach ($dir as $fileInfo) {
        if ($fileInfo->isDot() || ! $fileInfo->isDir()) {
            continue;
        }
        if (is_null($region)) {
            $systems = iterate($fileInfo->getRealPath(), $systems, $fileInfo);
        } elseif (is_null($constellation)) {
            $systems = iterate($fileInfo->getRealPath(), $systems, $region, $fileInfo);
        } else {
            $systems[] = [
                $fileInfo->getFilename(),
                $constellation->getFilename(),
                $region->getFilename(),
            ];
        }
    }
    return $systems;
}
