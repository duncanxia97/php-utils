<?php
/**
 * @author XJ.
 * @Date   2025/11/7
 */

namespace Fatbit\Utils\Captcha;

use Fatbit\Utils\Helper\Arr;
use Fatbit\Utils\Helper\Str;

/**
 * @author XJ.
 * @Date   2025/11/7
 * @method self setFont(string $fontUrl) 设置字体文件
 * @method self setLineCount(int|array $lineCount) 设置干扰线数量
 * @method self setDotCount(int|array $dotCount) 设置干扰点数量
 * @method self setWidth(int $width) 设置图片宽度
 * @method self setHeight(int $height) 设置图片高度
 * @method self setCharset(string $charset) 设置字符集
 * @method self setColorFlip(bool $colorFlip) 设置字体颜色是否翻转
 * @method self setFontColor(array $fontColor) 设置字体颜色
 * @method self setBackgroundColor(array $backgroundColor) 设置背景颜色
 */
class ImageCaptcha
{
    /**
     * 字体文件
     *
     * @var string
     */
    private $font = __DIR__ . '/fonts/verdana.ttf';

    /**
     * 字符集
     *
     * @var string
     */
    private string $charset = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';

    /**
     * 干扰线数量
     *
     * @var int|array
     */
    private int|array $lineCount = 20;

    /**
     * 干扰点数量
     *
     * @var int|array
     */
    private int|array $dotCount = 200;

    /**
     * 图片宽度
     *
     * @var int
     */
    private int $width = 120;

    /**
     * 图片高度
     *
     * @var int
     */
    private int $height = 40;

    /**
     * 字体颜色是否翻转
     *
     * @var bool
     */
    private bool $colorFlip = false;

    /**
     * 字体颜色
     *
     * @var array|int[]
     */
    protected array $fontColor = [255, 255, 255];

    /**
     * 背景颜色
     *
     * @var array|array[]
     */
    private array $backgroundColor = [[0, 120], [0, 120], [0, 120]];

    /**
     * @author XJ.
     * @Date   2025/11/7
     *
     * @param $method
     * @param $value
     */
    public function __call($method, $value)
    {
        if (strpos($method, 'set') === 0) {
            $property = lcfirst(substr($method, 3));
            if (property_exists($this, $property)) {
                $this->$property = $value;

                return $this;
            }
        }
        throw new \BadMethodCallException('Method ' . $method . ' not exists');
    }


    /**
     * 生成图片验证码
     *
     * @author XJ.
     * @Date   2025/9/8
     *
     * @param $length
     *
     * @return array
     * @throws \Random\RandomException
     */
    public function generateCaptcha($length = 4): array
    {
        ['code' => $code, 'base64' => $base64] = $this->generateImage($length);
        $key           = 'img-ctc-' . uniqid('', false) . '-' . Str::random(6);
        $encodeCaptcha = $this->encodeCaptcha($code, $key);

        return compact('key', 'base64', 'encodeCaptcha');
    }

    /**
     * 加密验证码
     *
     * @author XJ.
     * @Date   2025/9/8
     *
     * @param string $code
     * @param string $key
     *
     * @return string
     */
    private function encodeCaptcha(string $code, string $key): string
    {
        return md5(strtolower($code) . '=' . sha1(strtoupper($code)) . '-' . Arr::last(explode('-', $key)));
    }


    /**
     * 验证图片验证码
     *
     * @author XJ.
     * @Date   2025/9/8
     *
     * @param string $key           验证码key
     * @param string $code          验证码
     * @param string $encodeCaptcha 加密之后的验证码
     *
     * @return bool|null 验证结果
     */
    public function verifyCaptcha(string $key, string $code, string $encodeCaptcha): ?bool
    {
        return $this->encodeCaptcha($code, $key) == $encodeCaptcha;
    }

    /**
     * 获取干扰线数量
     *
     * @author XJ.
     * @Date   2025/9/8
     * @return int
     * @throws \Random\RandomException
     */
    private function getLineCount(): int
    {
        if (is_int($this->lineCount)) {
            return $this->lineCount;
        }

        return random_int(...$this->lineCount);
    }

    /**
     * 获取干扰点数量
     *
     * @author XJ.
     * @Date   2025/9/8
     * @return int
     * @throws \Random\RandomException
     */
    private function getDotCount(): int
    {
        if (is_int($this->dotCount)) {
            return $this->dotCount;
        }

        return random_int(...$this->dotCount);
    }

    /**
     * 获取背景色
     *
     * @author XJ.
     * @Date   2025/9/15
     * @return array
     * @throws \Random\RandomException
     */
    protected function getBgColor(): array
    {
        return [
            is_array($this->backgroundColor[0]) ? random_int($this->backgroundColor[0][0], $this->backgroundColor[0][1]) : $this->backgroundColor[0],
            is_array($this->backgroundColor[1]) ? random_int($this->backgroundColor[1][0], $this->backgroundColor[1][1]) : $this->backgroundColor[1],
            is_array($this->backgroundColor[2]) ? random_int($this->backgroundColor[2][0], $this->backgroundColor[2][1]) : $this->backgroundColor[2],
        ];
    }

    /**
     * 获取字体颜色
     *
     * @author XJ.
     * @Date   2025/9/15
     * @return array
     * @throws \Random\RandomException
     */
    protected function getFontColor(): array
    {
        return [
            is_array($this->fontColor[0]) ? random_int($this->fontColor[0][0], $this->fontColor[0][1]) : $this->fontColor[0],
            is_array($this->fontColor[1]) ? random_int($this->fontColor[1][0], $this->fontColor[1][1]) : $this->fontColor[1],
            is_array($this->fontColor[2]) ? random_int($this->fontColor[2][0], $this->fontColor[2][1]) : $this->fontColor[2],
        ];
    }

    /**
     * 生成图片验证码
     *
     * @author XJ.
     * @Date   2025/9/8
     *
     * @param int $length
     * @param int $width
     * @param int $height
     *
     * @return array
     * @throws \Random\RandomException
     */
    protected function generateImage(
        int $length = 4,
    ): array {
        $image = imagecreatetruecolor($this->width, $this->height);
        if ($image === false) {
            return [];
        }
        $bgColor   = $this->getBgColor();
        $fontColor = $this->getFontColor();
        if ($this->colorFlip) {
            $flagColor = $bgColor;
            $bgColor   = $fontColor;
            $fontColor = $flagColor;
        }
        $bgColor   = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        $fontColor = imagecolorallocate($image, $fontColor[0], $fontColor[1], $fontColor[2]);

        imagefill($image, 0, 0, $bgColor);

        $code = $this->generateRandomCode($length);

        $fontSize = 20;
        $padding  = 5;

        $charSlotWidth = ($this->width - $padding * 2) / $length;

        for ($i = 0; $i < $length; $i++) {
            $x = (int)($padding + ($charSlotWidth * $i) + random_int(0, 5));

            $y = random_int((int)($this->height * 0.65), (int)($this->height * 0.85));

            imagettftext(
                $image,
                $fontSize,
                random_int(-20, 20),
                $x,
                $y,
                $fontColor,
                $this->font,
                $code[$i],
            );
        }

        // 6. 绘制干扰线 (使用浅色以在深色背景上可见)
        for ($i = 0; $i < $this->getLineCount(); $i++) {
            $lineColor = imagecolorallocate($image, random_int(130, 220), random_int(130, 220), random_int(130, 220));
            imageline($image, random_int(0, $this->width), random_int(0, $this->height), random_int(0, $this->width), random_int(0, $this->height), $lineColor);
        }

        // 7. 绘制干扰点 (雪花) (同样使用浅色)
        for ($i = 0; $i < $this->getDotCount(); $i++) {
            $dotColor = imagecolorallocate($image, random_int(130, 220), random_int(130, 220), random_int(130, 220));
            imagesetpixel($image, random_int(0, $this->width), random_int(0, $this->height), $dotColor);
        }

        ob_start();
        imagepng($image);
        $imageData   = ob_get_clean();
        $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

        imagedestroy($image);

        return [
            'code'   => $code,
            'base64' => $imageBase64,
        ];
    }

    /**
     * 生成随机字符串
     *
     * @param int $length
     *
     * @return string
     */
    private function generateRandomCode(int $length): string
    {
        $code = '';
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $code .= $this->charset[random_int(0, $_len)];
        }

        return $code;
    }
}