<?php

namespace LightningPrism;

class LightningPrismSettings
{
    /**
     * Lightning Prism Settings
     *
     * Format: [
     *   'lightningAddress' => percentage,
     *   'lightningAddress' => percentage,
     * ]
     *
     * @var array
     */
    public array $settings;

    public function __construct(array $settings)
    {
        $this->validateSettings($settings);

        $this->settings = $settings;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Validate Settings
     *
     * Validates that the settings are valid
     *
     * @param array $settings
     *
     * @throws \Exception
     */
    private function validateSettings(array $settings): void
    {
        $this->validatePercentages($settings);
        $this->validateLightningAddressFormats($settings);
    }

    /**
     * Validate Prism Percentages
     *
     * Validates that all the split proportions add up to 100
     *
     * @param array $settings
     *
     * @throws \Exception
     */
    private function validatePercentages(array $settings): bool
    {
        $totalPercentage = 0;

        foreach ($settings as $lightningAddress => $percentage) {
            if (!is_int($percentage)) {
                throw new \Exception('Percentages must be integers');
            }

            if ($percentage < 0) {
                throw new \Exception('Percentages must be positive');
            }

            $totalPercentage += $percentage;
        }

        if ($totalPercentage !== 100) {
            throw new \Exception('Percentages must add up to 100. Total: ' . $totalPercentage . '%');
        }

        return true;
    }

    /**
     * Validate Lightning Address Formats
     *
     * Validates that all the lightning addresses are email format
     *
     * @param array $settings
     *
     * @throws \Exception
     */
    private function validateLightningAddressFormats(array $settings): bool
    {
        foreach ($settings as $lightningAddress => $percentage) {
            if (!filter_var($lightningAddress, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Lightning addresses must be email format');
            }
        }

        return true;
    }


}
