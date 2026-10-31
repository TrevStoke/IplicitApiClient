<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit ContactAccount API operations.
 */
class ContactAccountService extends AbstractService
{
    /**
     * Gets a list of contact accounts with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccounts(array $query = []): mixed
    {
        $path = '/api/ContactAccount';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Gets a list of contact accounts with a search filter.
     */
    public function paginateContactAccounts(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/ContactAccount';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Gets a list of contact accounts with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllContactAccounts(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateContactAccounts($query, $pageSize);
        yield from $paginator;
    }

    /**
     * Create a contact account
     *
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function createContactAccount(mixed $data = []): mixed
    {
        $path = '/api/ContactAccount';
        return $this->post($path, $data, []);
    }

    /**
     * Get a contact account
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccount(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef);
        return $this->get($path, $query);
    }

    /**
     * Update a contact account
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContactAccount(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Get the main contact details of the contact account
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountMainContact(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contact';
        return $this->get($path, $query);
    }

    /**
     * Update the main contact details
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContactAccountMainContact(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contact';
        return $this->patch($path, $data, []);
    }

    /**
     * Get the customer details of the contact account
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountCustomerDetails(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/customer';
        return $this->get($path, $query);
    }

    /**
     * Get the supplier details of the contact account
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountSupplierDefaults(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/supplier';
        return $this->get($path, $query);
    }

    /**
     * Get the resource details of the contact account
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountResource(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/resource';
        return $this->get($path, $query);
    }

    /**
     * Get the default bank details
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountDefaultBankAccount(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/defaultBankDetails';
        return $this->get($path, $query);
    }

    /**
     * Get all the bank details
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountBankAccounts(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/bankDetails';
        return $this->get($path, $query);
    }

    /**
     * Get the related contacts
     *
     * @param string|int $contactAccountRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountRelatedContacts(string|int $contactAccountRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts';
        return $this->get($path, $query);
    }

    /**
     * Add related contacts
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addContactAccountContact(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts';
        return $this->post($path, $data, []);
    }

    /**
     * Update existing contacts.  
            Existing contacts are matched to request contacts by ID, IntRef or Description and updated.
            Existing contacts that do not match any request contact are left unchanged.
            Request contacts that do not match any existing contact are added.
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContactAccountRelatedContacts(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts';
        return $this->patch($path, $data, []);
    }

    /**
     * Get an existing related contact
     *
     * @param string|int $contactAccountRef
     * @param string|int $contactRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getContactAccountRelatedContact(string|int $contactAccountRef, string|int $contactRef, array $query = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts/' . rawurlencode((string)$contactRef);
        return $this->get($path, $query);
    }

    /**
     * Update an existing related contact
     *
     * @param string|int $contactAccountRef
     * @param string|int $contactRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContactAccountContact(string|int $contactAccountRef, string|int $contactRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts/' . rawurlencode((string)$contactRef);
        return $this->patch($path, $data, []);
    }

    /**
     * Delete a related contact
     *
     * @param string|int $contactAccountRef
     * @param string|int $contactRef
     * @return mixed
     */
    public function deleteContactAccount(string|int $contactAccountRef, string|int $contactRef): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/contacts/' . rawurlencode((string)$contactRef);
        return $this->delete($path, null, []);
    }

    /**
     * Replace bank details
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function updateContactAccountBankDetails(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/bankDetails/default';
        return $this->patch($path, $data, []);
    }

    /**
     * Add an existing contact to a contact account.
     *
     * @param string|int $ca
     * @param string|int $cb
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function addContactToContactAccount(string|int $ca, string|int $cb, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$ca) . '/contactLinks/' . rawurlencode((string)$cb);
        return $this->post($path, $data, []);
    }

    /**
     * Remove an existing contact from a contact account.
     *
     * @param string|int $ca
     * @param string|int $cb
     * @return mixed
     */
    public function deleteContactFromContactAccount(string|int $ca, string|int $cb): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$ca) . '/contactLinks/' . rawurlencode((string)$cb);
        return $this->delete($path, null, []);
    }

    /**
     * LockContactAccount
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockContactAccount(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the contact account
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockContactAccount(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/unlock';
        return $this->post($path, $data, []);
    }

    /**
     * Lock the customer
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockCustomer(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/customer/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the customer
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockCustomer(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/customer/unlock';
        return $this->post($path, $data, []);
    }

    /**
     * Lock the supplier
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockSupplier(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/supplier/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the supplier
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockSupplier(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/supplier/unlock';
        return $this->post($path, $data, []);
    }

    /**
     * Lock the contact account resource
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function lockContactAccountResource(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/resource/lock';
        return $this->post($path, $data, []);
    }

    /**
     * Unlock the contact account resource
     *
     * @param string|int $contactAccountRef
     * @param mixed $data Request payload or body data
     * @return mixed
     */
    public function unlockContactAccountResource(string|int $contactAccountRef, mixed $data = []): mixed
    {
        $path = '/api/ContactAccount/' . rawurlencode((string)$contactAccountRef) . '/resource/unlock';
        return $this->post($path, $data, []);
    }

}
