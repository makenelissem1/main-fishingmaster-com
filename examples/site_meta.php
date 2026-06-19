<?php

/**
 * 站点元信息管理工具
 * 
 * 本文件仅用于演示组织站点元数据结构并提供描述文本生成能力
 */

/**
 * 获取站点默认元信息配置
 *
 * @return array 返回包含站点元数据的关联数组
 */
function getSiteMetaConfig(): array
{
    return [
        'site_name'        => 'Main Fishing Master',
        'site_url'         => 'https://main-fishingmaster.com',
        'site_keywords'    => ['捕鱼达人', '钓鱼技巧', '渔具评测', '水域指南'],
        'site_description' => '专业的捕鱼与钓鱼爱好者社区，分享捕鱼达人经验与技巧',
        'author'           => '捕鱼达人编辑部',
        'language'         => 'zh-CN',
        'charset'          => 'UTF-8',
        'version'          => '1.2.0',
        'last_updated'     => '2025-03-15',
    ];
}

/**
 * 根据元信息生成简短描述文本
 *
 * @param array $meta 站点元信息数组
 * @param int   $maxLength 描述最大长度，默认120
 * @return string 生成的描述文本
 */
function generateShortDescription(array $meta, int $maxLength = 120): string
{
    $base = $meta['site_name'] ?? '未知站点';
    $desc = $meta['site_description'] ?? '';
    $keywords = $meta['site_keywords'] ?? [];

    if (!empty($keywords)) {
        $kwStr = implode('、', array_slice($keywords, 0, 3));
        $base .= ' - 聚焦：' . $kwStr;
    }

    if (!empty($desc)) {
        $combined = $base . '。' . $desc;
    } else {
        $combined = $base;
    }

    if (mb_strlen($combined, 'UTF-8') > $maxLength) {
        $combined = mb_substr($combined, 0, $maxLength - 3, 'UTF-8') . '...';
    }

    return $combined;
}

/**
 * 从元信息数组中提取关键数据，用于前端展示
 *
 * @param array $meta 站点元信息
 * @return array 返回经过过滤和格式化的元数据子集
 */
function extractMetaForDisplay(array $meta): array
{
    $allowedKeys = ['site_name', 'site_url', 'site_keywords', 'description'];
    $display = [];

    foreach ($allowedKeys as $key) {
        if (array_key_exists($key, $meta)) {
            $value = $meta[$key];
            if (is_array($value)) {
                $display[$key] = array_map('htmlspecialchars', $value);
            } else {
                $display[$key] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
            }
        }
    }

    return $display;
}

/**
 * 将元信息数组转换为HTML meta标签字符串
 *
 * @param array $meta
 * @return string
 */
function metaToHtmlTags(array $meta): string
{
    $tags = '';

    if (!empty($meta['site_description'])) {
        $desc = htmlspecialchars($meta['site_description'], ENT_QUOTES, 'UTF-8');
        $tags .= '<meta name="description" content="' . $desc . '">' . "\n";
    }

    if (!empty($meta['site_keywords'])) {
        $kw = implode(', ', array_map('htmlspecialchars', $meta['site_keywords']));
        $tags .= '<meta name="keywords" content="' . $kw . '">' . "\n";
    }

    if (!empty($meta['author'])) {
        $author = htmlspecialchars($meta['author'], ENT_QUOTES, 'UTF-8');
        $tags .= '<meta name="author" content="' . $author . '">' . "\n";
    }

    if (!empty($meta['charset'])) {
        $tags .= '<meta charset="' . htmlspecialchars($meta['charset'], ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    return $tags;
}

// ---------- 示例使用 ----------
$meta = getSiteMetaConfig();

$shortDesc = generateShortDescription($meta);
echo "简短描述：{$shortDesc}\n";

$displayData = extractMetaForDisplay($meta);
print_r($displayData);

$htmlMeta = metaToHtmlTags($meta);
echo "HTML Meta标签：\n{$htmlMeta}";