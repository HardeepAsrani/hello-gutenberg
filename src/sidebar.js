/**
 * Internal block libraries
 */

const { __ } = wp.i18n;

const {
	PluginSidebar,
	PluginSidebarMoreMenuItem
} = wp.editPost;

const {
	PanelBody,
	TextControl
} = wp.components;

const {
	Component,
	Fragment
} = wp.element;

const { withSelect } = wp.data;

const { compose } = wp.compose;

const { registerPlugin } = wp.plugins;

class Hello_Gutenberg extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			key: '_hello_gutenberg_field',
			value: '',
		}

		wp.apiFetch( { path: `/wp/v2/posts/${this.props.postId}`, method: 'GET' } ).then(
			( data ) => {
				this.setState( { 
					value: data.meta._hello_gutenberg_field
				} );
				return data;
			},
			( err ) => {
				return err;
			}
		);
	}

	static getDerivedStateFromProps( nextProps, state ) {
		if ( ( nextProps.isPublishing || nextProps.isSaving ) && !nextProps.isAutoSaving ) {
			wp.apiRequest( { path: `/hello-gutenberg/v1/update-meta?id=${nextProps.postId}`, method: 'POST', data: state } ).then(
				( data ) => {
					return data;
				},
				( err ) => {
					return err;
				}
			);
		}
	}

	render() {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem
					target="hello-gutenberg-sidebar"
				>
					{ __( 'Hello Gutenberg' ) }
				</PluginSidebarMoreMenuItem>
				<PluginSidebar
					name="hello-gutenberg-sidebar"
					title={ __( 'Hello Gutenberg' ) }
				>
					<PanelBody>
						<TextControl
							label={ __( 'What\'s your name?' ) }
							value={ this.state.value }
							onChange={ ( value ) => { 
								this.setState( {
									value
								} );
							} }
						/>
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		)
	}
}

const HOC = withSelect( ( select, { forceIsSaving } ) => {
	const {
		getCurrentPostId,
		isSavingPost,
		isPublishingPost,
		isAutosavingPost,
	} = select( 'core/editor' );
	return {
		postId: getCurrentPostId(),
		isSaving: forceIsSaving || isSavingPost(),
		isAutoSaving: isAutosavingPost(),
		isPublishing: isPublishingPost(),
	};
} )( Hello_Gutenberg );

registerPlugin( 'hello-gutenberg', {
	icon: 'admin-site',
	render: HOC,
} );