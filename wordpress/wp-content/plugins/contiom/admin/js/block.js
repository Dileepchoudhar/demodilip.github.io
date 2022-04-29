( function( blocks, editor, element ) {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, PanelHeader, PanelRow, ToggleControl, TabPanel, SelectControl, Divider, FormGroup, ListGroup, Heading, TextControl } = wp.components;
	var el = element.createElement;

	blocks.registerBlockType( 'contiom/content-block-p', {
		title: 'Contiom: Content Paragraph', // The title of block in editor.
		icon: 'admin-comments', // The icon of block in editor.
		category: 'common', // The category of block in editor.
		attributes: {
			block_id: {
				type: 'string',
			},
			block_name: {
				type: 'string',
			},
			content: {
				type: 'string',
				default: 'Contiom Paragraph	',
			},
		},
		"supports": {
			"anchor": true,
			"className": false,
			"color": {
				"link": true
			},
			"typography": {
				"fontSize": true,
				"lineHeight": true
			},
			"__experimentalSelector": "p",
			"__unstablePasteTextInline": true
		},
		edit: function( props ) { 
			console.log(props);
			return (
				el( 'div', { className: props.className,  },
					el( InspectorControls, {},
				el( PanelBody, { title: 'contiom Settings', initialOpen: true },

					/* Text Field */
					el( PanelRow, {},
						el( TextControl,
							{
								label: 'Block ID',
								class : 'readonly-field',
								onChange: ( value ) => {
									props.setAttributes( { block_id: value } );
								},
								value: props.attributes.block_id
							}
						)
					),
					
					/* Text Field */
					el( PanelRow, {},
						el( TextControl,
							{
								label: 'Block Name',
								class : 'readonly-field',
								onChange: ( value ) => {
									props.setAttributes( { block_name: value } );
								},
								value: props.attributes.block_name
							}
						)
					),

					

				),

			),
					el(
						editor.RichText,
						{
							tagName: 'p',
							value: props.attributes.content,
							onChange: function( content ) {
								props.setAttributes( { content: content } );
							}
						}
					)
				)
			
			);
		}, 
		save: function( props ) {
			return (
				el( 'div', { className: props.className },
					el( editor.RichText.Content, {
						tagName: 'p',
						value: props.attributes.content,
					} )
				)
			);
		},
	} );	
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );