<?php

declare(strict_types=1);

// Arch presets
arch()->preset()->php();
arch()->preset()->security();

// General package arch tests
arch()->expect('Fkrzski\RobotsTxt')->classes()->toBeFinal();
arch()->expect('Fkrzski\RobotsTxt')->toUseStrictTypes();
arch()->expect('Fkrzski\RobotsTxt')->toUseStrictEquality();

arch()->expect('Tests')->toUseStrictTypes();
arch()->expect('Tests')->toUseStrictEquality();

// Specific namespace arch tests
arch()->expect('Fkrzski\RobotsTxt\Enums')->toBeEnums()->toHaveSuffix('Enum');
arch()->expect('Fkrzski\RobotsTxt\Contracts')->toBeInterfaces();
