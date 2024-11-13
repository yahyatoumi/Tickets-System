export const isAdmin = (auth) => {
    return auth.user.role === "admin";
}

export const isSupervisor = (auth) => {
    return auth.user.role === "supervisor";
}

export const isEndUser = (auth) => {
    return auth.user.role === "end_user";
}