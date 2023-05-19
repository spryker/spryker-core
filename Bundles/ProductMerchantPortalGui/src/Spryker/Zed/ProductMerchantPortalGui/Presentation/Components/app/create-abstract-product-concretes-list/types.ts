export interface ConcretesListFormChoice {
    label: string;
    value: string;
    hasNotificationMessage: boolean;
    hasError: boolean;
}

export interface ConcretesListForm {
    notificationMessage: string;
    errorMessage: string;
    value: string;
    name: string;
    choices: ConcretesListFormChoice[];
}
