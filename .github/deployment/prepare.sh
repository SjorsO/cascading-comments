base_directory=$1
ssh_user=$2
ssh_host=$3
ssh_port=$4
private_ssh_key=$5
ssh_known_hosts=$6

if [[ -z "$ssh_user" ]]; then
    echo "::error::The workflow variable \"\$ssh_user\" is not set."

    exit 1
fi

if [[ -z "$ssh_host" ]]; then
    echo "::error::The workflow variable \"\$ssh_host\" is not set."

    exit 1
fi

if [[ -z "$ssh_port" ]]; then
    echo "::error::The workflow variable \"\$ssh_port\" is not set."

    exit 1
fi

if [[ -z "$private_ssh_key" ]]; then
    echo "::error::The workflow variable \"\$private_ssh_key\" is not set. Add this value as a secret to your Github repository."

    exit 1
elif [[ "$private_ssh_key" =~ ^ssh-rsa[[:blank:]] ]] || [[ "$private_ssh_key" =~ [[:blank:]]PUBLIC[[:blank:]]KEY ]]; then
    echo "::error::The workflow variable \"\$private_ssh_key\" looks like a public key. It should be a private key."

    exit 1
fi

if [[ -z "$base_directory" ]]; then
    echo "::error::The workflow variable \"\$base_directory\" is not set."

    exit 1
elif [[ "$base_directory" =~ /current/?$ ]]; then
    echo "::error::The workflow variable \"\$base_directory\" points to the \"current\" directory. It should point one level higher, to the base directory."

    exit 1
fi

# Remove any trailing slash.
if [[ "$base_directory" =~ /$ ]]; then
    base_directory="${base_directory::-1}"
fi

echo "Preparing to connect to the remote server."

if [[ -n "$ssh_known_hosts" ]]; then
    mkdir -p ~/.ssh

    echo "$ssh_known_hosts" > ~/.ssh/known_hosts

    chmod 644 ~/.ssh/known_hosts
else
    echo "::warning::The workflow variable \"\$ssh_known_hosts\" is not set. We will connect to the remote server without verifying the host."

    # Disable host key verification.
    echo "StrictHostKeyChecking no" | sudo tee -a /etc/ssh/ssh_config >/dev/null

    # Prevent related warnings.
    echo "LogLevel ERROR" | sudo tee -a /etc/ssh/ssh_config >/dev/null
fi

# Start the SSH agent.
eval "$(ssh-agent)" >/dev/null

# Add our key to the SSH agent.
echo "$private_ssh_key" | tr -d "\r" | ssh-add -q - 2>/dev/null

# Generate a unique file name for the deployment artifacts.
remote_artifacts_path="/tmp/deployment-artifacts-$(tr -dc 0-9a-f </dev/urandom | head -c 8)"

echo "Uploading artifacts to the remote server."

scp -P "$ssh_port" "artifacts.tar.gz" "$ssh_user@$ssh_host:$remote_artifacts_path"

echo "Running the deployment script on the remote server."

ssh "$ssh_user@$ssh_host" -p "$ssh_port" "cat \"$base_directory/deployment/deploy.sh\" | bash -seo pipefail -- \"$remote_artifacts_path\" \"$base_directory\""
