async function fetchAPI( endPoint ) {
	return await fetch( `${ wp.ms4wpBaseURL }${ endPoint }` );
}

export async function getScheduledEvents( siteID ) {
	try {
		const response = await fetchAPI(
			`/appointment_type/all?siteId=${ siteID }`
		);

		if ( ! response.ok ) {
			throw new Error(
				`An error has occurred fetching events: ${ response.status }`
			);
		}

		return await response.json();
	} catch ( e ) {
		console.log( e.message );
	}
}

export async function getEventCategories( siteID ) {
	try {
		const response = await fetchAPI( `/category/all?siteId=${ siteID }` );

		if ( ! response.ok ) {
			throw new Error(
				`An error has occurred fetching event categories: ${ response.status }`
			);
		}

		return await response.json();
	} catch ( e ) {
		console.log( e.message );
	}
}

export async function getGroupServices( siteID ) {
	try {
		const response = await fetchAPI(
			`/appointment_type/bookable_group_services?siteId=${ siteID }&startDate=${ new Date().toISOString() }`
		);

		if ( ! response.ok ) {
			throw new Error(
				`An error has occurred fetching event categories: ${ response.status }`
			);
		}

		return await response.json();
	} catch ( e ) {
		console.log( e.message );
	}
}
