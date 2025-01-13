<?php

namespace App\Constants;

final class  PermissionsName
{

    const AccountView = 'account:view';
    const AccountCreate = 'account:create';
    const AccountUpdate = 'account:update';
    const AccountInvite = 'account:invite';
    const AccountDelete = 'account:delete';

    const BusinessView = 'business:view';
    const BusinessCreate = 'business:create';
    const BusinessUpdate = 'business:update';
    const BusinessDelete = 'business:delete';

    const DelegateView = 'delegate:view';
    const DelegateCreate = 'delegate:create';
    const DelegateUpdate = 'delegate:update';
    const DelegateDelete = 'delegate:delete';


    const GovernorateCreate = 'governorate:create';
    const GovernorateUpdate = 'governorate:update';
    const GovernorateDelete = 'governorate:delete';

    const LocationUpdate = 'location:update';
    const LocationDelete = 'location:delete';

    const OrderCreate = 'order:create';
    const OrderUpdate = 'order:update';
    const OrderUpdateStatus = 'order:updateStatus';
    const OrderDelete = 'order:delete';

    const PaymentView = 'payment:view';
    const PaymentCreate = 'payment:create';
    const PaymentUpdate = 'payment:update';
    const PaymentDelete = 'payment:delete';

    const PlanView = 'plan:view';
    const PlanCreate = 'plan:create';
    const PlanUpdate = 'plan:update';
    const PlanDelete = 'plan:delete';

    const RosterView = 'roster:view';
    const RosterCreate = 'roster:create';
    const RosterUpdate = 'roster:update';
    const RosterDelete = 'roster:delete';
    const RosterReceive = 'roster:receive';

    const StatementView = 'statement:view';
    const StatementCreate = 'statement:create';
    const StatementUpdate = 'statement:update';
    const StatementDelete = 'statement:delete';

    const ALL_PERMISSIONS = [
        self::AccountView,
        self::AccountCreate,
        self::AccountUpdate,
        self::AccountInvite,
        self::AccountDelete,
        self::BusinessView,
        self::BusinessCreate,
        self::BusinessUpdate,
        self::BusinessDelete,
        self::DelegateView,
        self::DelegateCreate,
        self::DelegateUpdate,
        self::DelegateDelete,
        self::LocationUpdate,
        self::LocationDelete,
        self::OrderCreate,
        self::OrderUpdate,
        self::OrderUpdateStatus,
        self::OrderDelete,
        self::PaymentView,
        self::PaymentCreate,
        self::PaymentUpdate,
        self::PaymentDelete,
        self::PlanView,
        self::PlanCreate,
        self::PlanUpdate,
        self::PlanDelete,
        self::RosterView,
        self::RosterCreate,
        self::RosterUpdate,
        self::RosterDelete,
        self::RosterReceive,
        self::StatementView,
        self::StatementCreate,
        self::StatementUpdate,
        self::StatementDelete,
    ];
}
