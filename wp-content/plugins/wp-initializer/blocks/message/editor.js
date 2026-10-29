/**
 * Editor UI for wp-initializer/message.
 *
 * block.json registers the block with WordPress. This file only supplies the
 * editor component. The front end is rendered by render.php.
 */
( function ( blocks, element, blockEditor, serverSideRender, i18n ) {
	var el = element.createElement;
	var useBlockProps = blockEditor.useBlockProps;
	var __ = i18n.__;
	var Render = serverSideRender.default || serverSideRender;

	blocks.registerBlockType( 'wp-initializer/message', {
		edit: function ( props ) {
			return el(
				'div',
				useBlockProps(),
				el( Render, {
					block: 'wp-initializer/message',
					attributes: props.attributes || {},
					EmptyResponsePlaceholder: function () {
						return el(
							'p',
							{},
							__( 'The initializer message will appear here.', 'wp-initializer' )
						);
					}
				} )
			);
		},
		save: function () {
			return null;
		}
	} );
}(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.serverSideRender,
	window.wp.i18n
) );
