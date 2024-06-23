<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\ConfigService;
use App\Service\Core\TranslatorService;
use DateTime;
use DateTimeInterface;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigHelper extends AbstractExtension
{
    public function __construct(
        private readonly ConfigService $configService,
        // private readonly TranslatorInterface $translator
    ) {
    }
    
    public function getFunctions(): array
    {
        return [
            // new TwigFunction('trans', [$this, 'trans']),
            new TwigFunction('dash', [$this, 'dash']),
            new TwigFunction('supportEmail', [$this, 'getSupportEmail']),
            new TwigFunction('slug', [$this, 'slug']),
            new TwigFunction('hash', [$this, 'hash']),
            new TwigFunction('timeAgo', [$this, 'timeAgo']),
            new TwigFunction('formatSizeUnits', [$this, 'formatSizeUnits']),
            new TwigFunction('dateTime', [$this, 'dateTime']),
            new TwigFunction('replaceLinkWithHref', [$this, 'replaceLinkInText']),
            new TwigFunction('count', [$this, 'getCount']),
            new TwigFunction('appName', [$this, 'getAppName']),
            new TwigFunction('year', [$this, 'getYear']),
            new TwigFunction('faCircle', [$this, 'getCircle']),
            new TwigFunction('circle', [$this, 'circle']),
            new TwigFunction('checkCircle', [$this, 'checkCircle']),
            new TwigFunction('faThumbsUp', [$this, 'getThumbsUp']),
            new TwigFunction('faThumbsDown', [$this, 'getThumbsDown']),
            new TwigFunction('appAuthor', [$this, 'getAppAuthor']),
            new TwigFunction('madeBy', [$this, 'getMadeBy']),
            new TwigFunction('version', [$this, 'getVersion']),
        ];
    }

    // public function trans(string $message, ?string $param = null, ?string $locale = 'en'): string
    // {
    //     if (empty($param)) {
    //         return $this->translator->trans($message, ['locale' => $locale]);
    //     }

    //     return sprintf($this->translator->trans($message, ['locale' => $locale]), $param);
    // }


    public function dash(): string
    {
        return '---';
    }

    public function slug(string $text): string
    {
        return str_replace(' ', '-', rtrim($text, '.'));
    }

    public function hash(?string $text = null): string
    {
        if (empty($text)) {
            return sha1(random_bytes(16));
        }

        return sha1($text);
    }

    public function timeAgo(DateTimeInterface $datetime, bool $fullDateTime = false): string
    {
        $now = new DateTime();
        $ago = '';

        try {
            $ago = new DateTime($datetime->format('Y-m-d H:i:s'));
        } catch (Exception $e) {
        }

        $differentTime = $now->diff($ago);

        $differentTime->w = floor($differentTime->d / 7);
        $differentTime->d -= $differentTime->w * 7;

        $components = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        foreach ($components as $key => &$value) {
            if ($differentTime->$key) {
                $value = $differentTime->$key . ' ' . $value . ($differentTime->$key > 1 ? 's' : '');
            } else {
                unset($components[$key]);
            }
        }

        unset($value);

        if (!$fullDateTime) {
            $components = array_slice($components, 0, 1);
        }

        return $components ? implode(', ', $components) . ' ago' : 'just now';
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function dateTime(string $format = 'd.m.Y'): string
    {
        return (new DateTime('now'))->format($format);
    }

    public function getStripTags(string $text): string
    {
        return strip_tags($text);
    }

    public function replaceLinkInText(string $text): string
    {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\(\w+\)|([^,[:punct:]\s]|/))#', $text, $matches);

        if (count($matches[0]) <= 0) {
            return $text;
        }

        $result = '';

        foreach ($matches[0] as $link) {
            $result = str_replace($link, '<a href="' . $link . '" target="_blank">' . $link . '</a>', $text);
        }

        return $result;
    }

    public function getCount(mixed $obj): int
    {
        if (is_countable($obj)) {
            return count($obj);
        }

        return 0;
    }

    public function getYear(): string
    {
        return (new DateTime())->format('Y');
    }

    public function getCircle(string $color): void
    {
        echo "<span class='fa fa-circle fa-sm $color'></span>";
    }

    public function circle(string $color): void
    {
        echo "<span class='bi bi-circle-fill $color'></span>";
    }

    public function checkCircle(string $color): void
    {
        echo "<span class='bi bi-check2-circle fs-6 $color'></span>";
    }

    public function getThumbsUp(): void
    {
        echo "<span class='fa fa-thumbs-up fa-sm text-success'></span>";
    }

    public function getThumbsDown(?string $color = null): void
    {
        $color = $color ?? 'text-danger';

        echo "<span class='fa fa-thumbs-down fa-sm $color'></span>";
    }

    public function getSupportEmail(): string
    {
        return $this->configService->getParameter('support_emal');
    }

    public function getAppName(): string
    {
        return $this->configService->getParameter('app_name');
    }

    public function getMadeBy(): string
    {
        return $this->configService->getParameter('app_made_by');
    }

    public function getAppAuthor(): string
    {
        return $this->configService->getParameter('app_author');
    }

    public function getVersion(): string
    {
        return 'v' . $this->configService->getParameter('app_version');
    }
}
