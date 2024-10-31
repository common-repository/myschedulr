import { Component } from 'react';
import PropTypes from 'prop-types';
import Tab from './tab';

class Tabs extends Component {
	static propTypes = {
		children: PropTypes.instanceOf( Array ).isRequired,
		layout: PropTypes.instanceOf( String ),
	};

	constructor( props ) {
		super( props );

		this.state = {
			activeTab: this.props.children[ 0 ].props.label,
		};
	}

	onClickTabItem = ( tab ) => {
		this.setState( { activeTab: tab } );
	};

	render() {
		const {
			onClickTabItem,
			props: { children },
			state: { activeTab },
		} = this;

		return (
			<div className="nms-tabs-wrapper">
				<ul className="nms-tabs-nav">
					{ children.map( ( child ) => {
						const { label } = child.props;
						return (
							<Tab
								activeTab={ activeTab }
								key={ label }
								label={ label }
								onClick={ onClickTabItem }
							/>
						);
					} ) }
				</ul>
				<div className="nms-tabs-content">
					<div
						className={ `nms-cards layout-${ this.props.layout }` }
					>
						{ children.map( ( child ) => {
							if ( child.props.label !== activeTab )
								return undefined;
							return child.props.children;
						} ) }
					</div>
				</div>
			</div>
		);
	}
}

export default Tabs;
