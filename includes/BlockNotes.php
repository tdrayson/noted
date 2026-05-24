<?php

declare(strict_types=1);

namespace Noted;

/**
 * Server-side registration of block-level note attributes.
 *
 * Injecting attributes only via the `blocks.registerBlockType` JS filter is
 * not enough for dynamic blocks (and others with strict REST validation):
 * WordPress rejects unknown attributes when rendering or saving. Registering
 * here propagates the schema to the editor with higher priority than
 * client-only patches.
 */
final class BlockNotes
{
    public const ATTR_NOTE      = 'notedNote';
    public const ATTR_NOTE_USER = 'notedNoteUser';

    /**
     * Register WordPress hooks.
     */
    public function register(): void
    {
        add_filter('register_block_type_args', [$this, 'addNoteAttributes'], 10, 2);
    }

    /**
     * Add notedNote + notedNoteUser to every block type registered on the server.
     *
     * @param array<string, mixed> $args       Block type registration arguments.
     * @param string               $block_type Block name (e.g. core/paragraph).
     * @return array<string, mixed>
     */
    public function addNoteAttributes(array $args, string $_block_type): array
    {
        if (! isset($args['attributes']) || ! is_array($args['attributes'])) {
            $args['attributes'] = [];
        }

        $args['attributes'][self::ATTR_NOTE] = [
            'type'    => 'string',
            'default' => '',
        ];
        $args['attributes'][self::ATTR_NOTE_USER] = [
            'type'    => 'string',
            'default' => '',
        ];

        return $args;
    }
}
