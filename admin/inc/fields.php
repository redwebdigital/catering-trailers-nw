<?php
/**
 * Declarative settings screens.
 *
 * A page describes its fields as data and calls settings_page(). That keeps
 * Business Details, Social, Tracking, Content and Global SEO to a spec each
 * instead of five nearly identical files, so a change to validation or layout
 * happens once.
 */

declare(strict_types=1);

/**
 * @param array $fields  key => [label, type, hint, extra]
 *                       types: text, tel, email, url, textarea, code, select, checkbox, number
 */
function settings_page(string $group, array $fields, string $successMsg = 'Saved.'): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    csrf_check();

    $save = [];
    foreach ($fields as $key => $spec) {
        $type = $spec['type'] ?? 'text';
        if ($type === 'checkbox') {
            $save[$key] = isset($_POST[field_name($key)]) ? '1' : '0';
            continue;
        }
        $raw = (string)($_POST[field_name($key)] ?? '');
        $raw = str_replace(["\r\n", "\r"], "\n", $raw);
        // strip control characters, keep newlines and tabs
        $raw = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $raw) ?? '';
        $raw = trim($raw);

        if ($type === 'email' && $raw !== '' && !filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            flash('That email address does not look valid, nothing was saved.', 'err');
            return;
        }
        if ($type === 'url' && $raw !== '' && !preg_match('#^https?://#i', $raw)) {
            $raw = 'https://' . ltrim($raw, '/');
        }
        $save[$key] = mb_substr($raw, 0, ($type === 'textarea' || $type === 'code') ? 20000 : 500);
    }

    settings_set_many($save, $group);
    flash($successMsg);
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

/** Setting keys contain dots, which PHP mangles in POST names. */
function field_name(string $key): string { return str_replace('.', '__', $key); }

function render_fields(array $fields): void
{
    foreach ($fields as $key => $spec) {
        $type  = $spec['type'] ?? 'text';
        $label = $spec['label'] ?? $key;
        $hint  = $spec['hint'] ?? '';
        $val   = (string)setting($key, $spec['default'] ?? '');
        $name  = field_name($key);
        $id    = 'f_' . $name;
        $count = isset($spec['count']) ? ' data-count="' . (int)$spec['count'] . '"' : '';

        if ($type === 'heading') {
            echo '<h3 style="margin:1.6rem 0 .8rem">' . e($label) . '</h3>';
            if ($hint) echo '<p class="card__hint">' . e($hint) . '</p>';
            continue;
        }

        echo '<div class="field">';

        if ($type === 'checkbox') {
            echo '<label class="check" for="' . e($id) . '">';
            echo '<input type="checkbox" id="' . e($id) . '" name="' . e($name) . '" value="1"'
               . ($val === '1' ? ' checked' : '') . '>';
            echo '<span>' . e($label) . ($hint ? ' <span class="hint">' . e($hint) . '</span>' : '') . '</span>';
            echo '</label></div>';
            continue;
        }

        echo '<label for="' . e($id) . '">' . e($label) . '</label>';

        switch ($type) {
            case 'textarea':
            case 'code':
                echo '<textarea class="textarea' . ($type === 'code' ? ' textarea--code' : '')
                   . '" id="' . e($id) . '" name="' . e($name) . '" rows="'
                   . (int)($spec['rows'] ?? 4) . '"' . $count . '>' . e($val) . '</textarea>';
                break;
            case 'select':
                echo '<select class="select" id="' . e($id) . '" name="' . e($name) . '">';
                foreach (($spec['options'] ?? []) as $ov => $ol) {
                    echo '<option value="' . e((string)$ov) . '"' . ($val === (string)$ov ? ' selected' : '')
                       . '>' . e((string)$ol) . '</option>';
                }
                echo '</select>';
                break;
            default:
                echo '<input class="input" type="' . e($type) . '" id="' . e($id) . '" name="'
                   . e($name) . '" value="' . e($val) . '"'
                   . (isset($spec['placeholder']) ? ' placeholder="' . e($spec['placeholder']) . '"' : '')
                   . $count . '>';
        }

        if ($hint) echo '<span class="hint">' . e($hint) . '</span>';
        echo '</div>';
    }
}

function save_bar(string $label = 'Save changes'): void
{
    echo '<div class="sticky-save"><button class="btn btn--accent" type="submit">'
       . e($label) . '</button></div>';
}
