/**
 * Block dependencies
 */

import classnames from 'classnames';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	RichText,
	InspectorControls,
	BlockControls,
} = wp.editor;

const { 
	PanelBody,
	TextareaControl,
	TextControl,
	Dashicon,
	Toolbar,
	Button,
	Tooltip,
} = wp.components;

/**
 * Register block
 */
export default registerBlockType( 'hello-gutenberg/click-to-tweet', {
	// Block Title
	title: __( 'Gutenberg Examples - Click to Tweet' ),
	// Block Description
	description: __( 'An example block of Click to Tweet.' ),
	// Block Category
	category: 'common',
	// Block Icon
	icon: 'twitter',
	// Block Keywords
	keywords: [
		__( 'Twitter' ),
		__( 'Tweet' ),
		__( 'Social Media' ),
	],
	attributes: {
		tweet: {
			type: 'string',
		},
		tweetsent: {
			type: 'string',
		},
		button: {
			type: 'string',
			default: __( 'Tweet' ),
		},
		theme: {
			type: 'boolean',
			default: false,
		},
	},
	// Defining the edit interface
	edit: props => {
		const onChangeTweet = value => {
			props.setAttributes( { tweet: value } );
		};
		const onChangeTweetSent = value => {
			props.setAttributes( { tweetsent: value } );
		};
		const onChangeButton = value => {
			props.setAttributes( { button: value } );
		};
		const toggletheme = value => {
			props.setAttributes( { theme: !props.attributes.theme } );
		};
		return [
			!! props.isSelected && (
				<BlockControls key="custom-controls">
					<Toolbar
						className='components-toolbar'
					>
						<Tooltip text={ __( 'Alternative Design' )	}>
							<Button
								className={ classnames(
									'components-icon-button',
									'components-toolbar__control',
									{ 'is-active': props.attributes.theme },
								) }
								onClick={ toggletheme }
							>
								<Dashicon icon="tablet" />
							</Button>
						</Tooltip>
					</Toolbar>
				</BlockControls>
			),
			!! props.isSelected && (
				<InspectorControls key="inspector">
					<PanelBody title={ __( 'Tweet Settings' ) } >
						<TextareaControl
							label={ __( 'Tweet Text' ) }
							value={ props.attributes.tweetsent	}
							onChange={ onChangeTweetSent }
							help={ __( 'You can add hashtags and mentions here that will be part of the actual tweet, but not of the display on your post.' ) }
						/>
						<TextControl
							label={ __( 'Button Text' ) }
							value={ props.attributes.button }
							onChange={ onChangeButton }
						/>
					</PanelBody>
				</InspectorControls>
			),
			<div className={ props.className }>
				<div className={ ( props.attributes.theme ? 'click-to-tweet-alt' : 'click-to-tweet' ) }>
					<div className="ctt-text">
						<RichText
							format="string"
							formattingControls={ [] }
							placeholder={ __( 'Tweet, tweet!' ) }
							onChange={ onChangeTweet }
							value={ props.attributes.tweet }
						/>
					</div>
					<p>
						<a className="ctt-btn">
							{ props.attributes.button }
						</a>
					</p>
				</div>
			</div>
		];
	},
	// Defining the front-end interface
	save() {
		// Rendering in PHP
		return null;
	},
});
