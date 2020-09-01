<?php

namespace Envms\Osseus\Utils;

/**
 * Class Net
 */
class Net
{
    /**
     * @param $address
     * @param $subnetMask
     *
     * @return string
     */
    public static function getIpv4Subnet($address, $subnetMask = '255.255.0.0')
    {
        return long2ip(ip2long($address) & ip2long($subnetMask));
    }

    /**
     * @param     $address
     * @param int $prefixLength
     *
     * @return bool|string
     */
    public static function getIpv6Prefix($address, int $prefixLength = 64)
    {
        $binaryPrefix = '';
        $remainingBits = $prefixLength;
        $binaryAddress = inet_pton($address);

        for ($byte = 0; $byte < 16; ++$byte) {
            // get the first byte of the series
            $currentByte = ord(substr($binaryAddress, $byte, 1));

            // get the bit-mask based on how many bits we want to copy
            $copyBits = max(0, min(8, $remainingBits));
            $mask = 256 - (2 ** (8 - $copyBits));

            // apply the mask to the byte
            $currentByte &= $mask;

            // append the byte to the prefix
            $binaryPrefix .= chr($currentByte);

            // remaining bits minus 1 byte
            $remainingBits -= 8;
        }

        return inet_ntop($binaryPrefix);
    }

    /**
     * @param $address
     *
     * @return bool|string
     */
    public static function getIpSubnet($address)
    {
        // determine whether the address is IPv4 or IPv6
        if (filter_var($address, FILTER_FLAG_IPV6) !== false) {
            return self::getIpv6Prefix($address);
        }

        return self::getIpv4Subnet($address);
    }

    /**
     * @param     $address
     * @param     $addressPrefix
     * @param int $prefixLength
     *
     * @return bool
     */
    public static function matchIpv6Cidr($address, $addressPrefix = null, int $prefixLength = 64)
    {
        if ($addressPrefix !== null) {
            $addressPrefix = inet_pton($addressPrefix);
        } else {
            $addressPrefix = inet_pton(self::getIpv6Prefix($address, $prefixLength));
        }

        $address = inet_pton($address);
        $binMask = self::ipv6MaskToByteArray($prefixLength);

        return ($address & $binMask) === $addressPrefix;
    }

    /**
     * @param $subnetMask
     *
     * @return string
     */
    protected static function ipv6MaskToByteArray($subnetMask)
    {
        $addr = str_repeat("f", $subnetMask / 4);

        switch ($subnetMask % 4) {
            case 0:
                break;
            case 1:
                $addr .= "8";
                break;
            case 2:
                $addr .= "c";
                break;
            case 3:
                $addr .= "e";
                break;
        }

        $addr = str_pad($addr, 32, '0');
        $addr = pack("H*", $addr);

        return $addr;
    }
}
