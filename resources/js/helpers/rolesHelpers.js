export const isAdmin = (auth) => {
    return auth.user.role === "admin";
}

export const isSupervisor = (auth) => {
    return auth.user.role === "supervisor";
}

export const isEndUser = (auth) => {
    return auth.user.role === "end_user";
}

export const isYourTicket = (auth, thicketId) => {
    return auth.user.id === thicketId || isAdmin(auth);
}

const FIELDS = ["title", "description", "status", "submitter", "tech"];

export const canSeeFieldOfTicket = (auth, field) => {
    if (!FIELDS.includes(field)) {
        throw new Error(`Field "${field}" is not a valid field.`);
    }

    // title & description & status
    if (field === "title" || field === "description" || field === "status"){
        return true;
    }

    // submitter
    if (field === "submitter"){
        return !isEndUser(auth);
    }

    // tech
    if (field === "tech"){
        return isAdmin(auth);
    }
}

export const canEditFieldOfTicket = (auth, field, submiterId) => {
    if (!FIELDS.includes(field)) {
        throw new Error(`Field "${field}" is not a valid field.`);
    }

    // title & description
    if ((field === "title" || field === "description")){
        return isEndUser(auth) || auth.user.id === submiterId;
    }

    // status
    if (field === "status"){
        return !isEndUser(auth);
    }

    // submitter cant be modified

    // tech
    if (field === "tech"){
        return isAdmin(auth);
    }
}